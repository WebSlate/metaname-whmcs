<?php // modified from https://bitbucket.org/jbg/php-json-rpc/src
class JsonRpcFault extends Exception {}

class JsonRpcClient {
	private $uri;

	public function __construct( $uri ) {
		$this->uri = $uri;
	}

	private function generateId() {
		$chars = array_merge( range( 'A', 'Z' ), range( 'a', 'z' ), range( 0, 9 ) );
		$id = '';
		for ( $c = 0; $c < 16; ++$c ) {
			$id .= $chars[mt_rand( 0, count( $chars ) - 1 )];
		}	
		return $id;
	}

	public function __call( $name, $arguments ) {
		$request = array(
			'jsonrpc' => '2.0',
			'method'  => $name,
			'params'  => $arguments,
			'id'      => $this->generateId()
		);
		$ctx = stream_context_create( array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'User-Agent: PHP JsonRpcClient 2.0\r\nAccept: application/json\r\nContent-Type: application/json\r\n',
				'content' => json_encode( $request )
			)
		) );
		if ( ( $jsonResponse = file_get_contents( $this->uri, false, $ctx ) ) === false ) {
			throw new JsonRpcFault( 'API response failed', -32603 );
		}
		if ( ( $response = json_decode( $jsonResponse ) ) === null ) {
			throw new JsonRpcFault( 'API response cannot be decoded', -32603 );
		}
		if ( $response->id != $request['id'] ) {
			throw new JsonRpcFault( 'Mismatched API response ID', -32603 );
		}
		if ( property_exists( $response, 'error' ) ) {
			throw new JsonRpcFault( $response->error->message, $response->error->code );
		}
		if ( property_exists( $response, 'result' ) ) {
			return $response->result;
		}
		throw new JsonRpcFault( 'Invalid API response', -32603 );
	}
}
