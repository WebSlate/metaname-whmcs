<?php
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

	private function getError( $e )
	{
		switch ( $e ) {
			case '-1':
				return 'Authentication failed';
			break; case '-2':
				return 'Bidding closed';
			break; case '-3':
				return 'Invalid bid';
			break; case '-4':
				return 'Invalid domain name';
			break; case '-5':
				return 'Domain name not yet found';
			break; case '-6':
				return 'No account default contact';
			break; case '-7':
				return 'Invalid term';
			break; case '-8':
				return 'Invalid contact';
			break; case '-9':
				return 'Invalid name server';
			break; case '-10':
				return 'Invalid URI';
			break; case '-11':
				return 'Transaction declined';
			break; case '-12':
				return 'DNS hosting not enabled';
			break; case '-13':
				return 'HTTP redirection is enabled';
			break; case '-14':
				return 'Domain name already exists';
			break; case '-15':
				return 'Invalid UDAI';
			break; case '-16':
				return 'Invalid DNS record';
			break; case '-17':
				return 'DNS record not found';
			break; case '-32000':
				return 'Internal server error';
			break; case '-32600':
				return 'Invalid JSON-RPC request';
			break; case '-32601':
				return 'Method not found';
			break; case '-32602':
				return 'Invalid method parameters';
			break; case '-32603':
				return 'Internal JSON-RPC error';
			break; case '-32700':
				return 'JSON parse error';
			break;
		}
		return false;
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
				'header'  => 'Content-Type: application/json\r\n',
				'content' => json_encode( $request )
			)
		) );
		if ( ( $jsonResponse = file_get_contents( $this->uri, false, $ctx ) ) === false ) {
			throw new JsonRpcFault( 'file_get_contents failed', -32603 );
		}
		if ( ( $response = json_decode( $jsonResponse ) ) === null ) {
			throw new JsonRpcFault( 'JSON cannot be decoded', -32603 );
		}
		if ( $response->id != $request['id'] ) {
			throw new JsonRpcFault( 'Mismatched JSON-RPC IDs', -32603 );
		}
		if ( property_exists( $response, 'error' ) ) {
			throw new JsonRpcFault( $response->error->message, $response->error->code );
		} else if ( property_exists( $response, 'result' ) ) {
			if ( ( $r = $this->getError( $response->result ) ) === false ) {
				return $response->result;
			} else {
				return array( 'error', $r );
			}
		} else {
			throw new JsonRpcFault( 'Invalid JSON-RPC response', -32603 );
		}
	}
}