<?php
/* ------------------------------------------------------------------------

   PHPresolver - PHP DNS resolver library
                 Version 1.1

   Copyright (c) 2001, 2002 Moriyoshi Koizumi <koizumi@ave.sytes.net>
   All Rights Reserved.

   This library is free software; you can redistribute it and/or modify it
   under the terms of the GNU Lesser General Public License as published
   by the Free Software Foundation; either version 2.1 of the License, or any
   later version.

   This library is distributed in the hope that it will be useful, but
   WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
   or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
   License for more details.

   You should have received a copy of the GNU Lesser General Public License
   along with this library; if not,
   write to the Free Software Foundation, Inc.,
   59 Temple Place, Suite 330, Boston, MA 02111-1307  USA

  ------------------------------------------------------------------------
*/

	define( "DNS_RECORDTYPE_A", 1 );
	define( "DNS_RECORDTYPE_NS", 2 );
	define( "DNS_RECORDTYPE_CNAME", 5 );
	define( "DNS_RECORDTYPE_SOA", 6 );
	define( "DNS_RECORDTYPE_PTR", 12 );
	define( "DNS_RECORDTYPE_MX", 15 );

	define( "DNS_RECORDTYPE_AAAA", 28 );

	define( "DNS_RECORDTYPE_ANY", 255 );
	
	define( "DNS_RECORDTYPE_TXT", 16 );

/*
	list of record types not yet implemented

	define( "DNS_RECORDTYPE_A6", 38 );
	define( "DNS_RECORDTYPE_NULL", 10 );
	define( "DNS_RECORDTYPE_OPT", 41 );
	define( "DNS_RECORDTYPE_TKEY", 249 );
	define( "DNS_RECORDTYPE_TSIG", 250 );
	define( "DNS_RECORDTYPE_IXFR", 251 );
	define( "DNS_RECORDTYPE_AXFR", 252 );
	define( "DNS_RECORDTYPE_MAILB", 253 );
	define( "DNS_RECORDTYPE_MAILA", 254 );
	define( "DNS_RECORDTYPE_MD", 3 );
	define( "DNS_RECORDTYPE_MF", 4 );
	define( "DNS_RECORDTYPE_MB", 7 );
	define( "DNS_RECORDTYPE_MG", 8 );
	define( "DNS_RECORDTYPE_MR", 9 );
	define( "DNS_RECORDTYPE_WKS", 11 );
	define( "DNS_RECORDTYPE_HINFO", 13 );
	define( "DNS_RECORDTYPE_MINFO", 14 );
	define( "DNS_RECORDTYPE_RP", 17 );
	define( "DNS_RECORDTYPE_AFSDB", 18 );
	define( "DNS_RECORDTYPE_X25", 19 );
	define( "DNS_RECORDTYPE_ISDN", 20 );
	define( "DNS_RECORDTYPE_RT", 21 );
	define( "DNS_RECORDTYPE_NSAP", 22 );
	define( "DNS_RECORDTYPE_NSAP_PTR", 23 );
	define( "DNS_RECORDTYPE_SIG", 24 );
	define( "DNS_RECORDTYPE_KEY", 25 );
	define( "DNS_RECORDTYPE_PX", 26 );
	define( "DNS_RECORDTYPE_GPOS", 27 );
	define( "DNS_RECORDTYPE_LOC", 29 );
	define( "DNS_RECORDTYPE_NXT", 30 );
	define( "DNS_RECORDTYPE_EID", 31 );
	define( "DNS_RECORDTYPE_NIMLOC", 32 );
	define( "DNS_RECORDTYPE_SRV", 33 );
	define( "DNS_RECORDTYPE_ATMA", 34 );
	define( "DNS_RECORDTYPE_NAPTR", 35 );
	define( "DNS_RECORDTYPE_KX", 36 );
	define( "DNS_RECORDTYPE_CERT", 37 );
*/

	define( "DNS_OPCODE_QUERY" , 0x0000 );
	define( "DNS_OPCODE_IQUERY", 0x0800 );
	define( "DNS_OPCODE_STATUS", 0x1000 );
	define( "DNS_OPCODE_NOTIFY", 0x1800 );
	define( "DNS_OPCODE_UPDATE", 0x2000 );

	define( "DNS_RCODE_SUCCESSFUL"   , 0x0001 );
	define( "DNS_RCODE_MALFORMATED"  , 0x0002 );
	define( "DNS_RCODE_FAILEDSERVER" , 0x0003 );
	define( "DNS_RCODE_NAMEERROR"    , 0x0004 );
	define( "DNS_RCODE_UNIMPLEMENTED", 0x0005 );
	define( "DNS_RCODE_REFUSED"      , 0x0006 );

	define( "DNS_HEADERSPEC_IS_RESPONSE"         , 0x8000 );
	define( "DNS_HEADERSPEC_OPCODE_MASK"         , 0x7800 );
	define( "DNS_HEADERSPEC_AUTHORITIVE_ANSWER"  , 0x0400 );
	define( "DNS_HEADERSPEC_TRUNCATED"           , 0x0200 );
	define( "DNS_HEADERSPEC_RECURSION_DESIRED"   , 0x0100 );
	define( "DNS_HEADERSPEC_RECURSION_AVAILABLE" , 0x0080 );
	define( "DNS_HEADERSPEC_RESPONSE_SPEC_MASK"  , 0x0480 );
	define( "DNS_HEADERSPEC_QUERY_SPEC_MASK"     , 0x0300 );
	define( "DNS_HEADERSPEC_RESERVED"            , 0x0e00 );
	define( "DNS_HEADERSPEC_RESULT_CODE_MASK"    , 0x000f );

	define( "DNS_CLASS_INTERNET"                 , 0x0001 );

	define( "DNS_UDP_PACKET_MAX_LENGTH", 512 );

	class	DNSRecord
	{
		public $type;
		public $name;
		public $dclass;
		public $ttl;
		public $specific_fields;

		function DNSRecord(
		  $name,
		  $type,
		  $dclass = DNS_CLASS_INTERNET,
		  $ttl = 0,
		  $specific_fields = false ) {
			$this->name = $name;
			$this->type = $type;
			$this->dclass = $dclass;
			$this->ttl = $ttl;
			$this->specific_fields = $specific_fields;
		}
		function &getTypeSpecificField( $name ) {
			if( $this->specific_fields ) {
				return $this->specific_fields[$name];
			}
			return false;
		}
	}
	class	DNSResolver
	{
		public $port;
		public $nameserver;
		public $timeout;
		function DNSResolver( $nameserver, $port = 53, $timeout = 1000000 ) {
			$this->port = $port;
			$this->nameserver = $nameserver;
			$this->timeout = $timeout;
		}
		function sendQuery( $dnsquery, $useTCP = false ) {
			$answer = false;
			$out_buf = $dnsquery->asOctets( false );
			$out_buf_len = strlen( $out_buf );
			if( $out_buf ) {
				if( $useTCP == false && $out_buf_len <= DNS_UDP_PACKET_MAX_LENGTH ) {
					/* connection by UDP */
					if( ( $sock = fsockopen( 'udp://'.$this->nameserver, $this->port, $this->timeout ) ) === false ) {
						return false;
					}
					socket_set_blocking( $sock, true );
					if( fwrite( $sock, $out_buf ) == $out_buf_len ) {
						$answer = new DNSAnswer( $sock, DNS_UDP_PACKET_MAX_LENGTH );
					}
					fclose( $sock );
				} else {
					/* connection by TCP */
					if( ( $sock = fsockopen( $this->nameserver, $this->port, $this->timeout ) ) === false ) {
						return false;
					}
					socket_set_blocking( $sock, true );
					if( fwrite( $sock, pack( 'n', $out_buf_len ) ) == 2 &&
					    fwrite( $sock, $out_buf ) == $out_buf_len ) {
						$tmp = unpack( 'nl', fread( $sock, 2 ) );
						$limit_length = $tmp['l'];
						print $limit_length;
						$answer = new DNSAnswer( $sock, $limit_length );
					}
					fclose( $sock );
				}
			}
			return $answer;
		}
	}

	class	DNSQuery 
	{
		public $id; // 1 - 65535
		public $header_opcode;
		public $query_record;
		public $flags;

		function DNSQuery( &$dnsrecord, $flags = DNS_HEADERSPEC_RECURSION_DESIRED )
		{
			$this->id = rand( 1, 255 ) | ( rand( 0, 255 ) << 8 );
			$this->flags = $flags & DNS_HEADERSPEC_QUERY_SPEC_MASK;
			$this->header_opcode = DNS_OPCODE_QUERY;
			$this->query_record = &$dnsrecord;
		}
		function asOctets() {
			if( $this->query_record->name === false ) { return false; }
			$buf = '';
			$buf .= pack( "nnnnnn", $this->id, DNS_OPCODE_QUERY | $this->flags, 1, 0, 0, 0 );
			$buf .= $this->query_record->name->asOctets();
			$buf .= pack( "nn", $this->query_record->type, $this->query_record->dclass );
			return $buf;
		}
	}

	class	DNSMessageParser
	{
		public $stream;
		public $nbytes_read;
		public $octets;
		public $limit;

		function DNSMessageParser( $stream, $limit ) {
			$this->stream = $stream;
			$this->nbytes_read = 0;
			$this->octets = '';
			$this->limit = $limit;
		}
		function readStreamDirectly( $nbytes ) {
			if( ( $this->limit -= $nbytes ) < 0 ) {
				return false;
			}
			$buf = fread( $this->stream, $nbytes );
			$this->octets .= $buf;
			$this->nbytes_read += $nbytes;
			return $buf;
		}

		function readBufferedStream( $nbytes, $offset ) {
			if( $offset < $this->nbytes_read ) {
				$diff = $this->nbytes_read - $offset;
				if( $nbytes <= $diff ) {
					return substr( $this->octets, $offset, $nbytes );
				} else {
					$buf = substr( $this->octets, $offset, $diff );
					$nbytes -= $diff;
				}
			} else {
				$buf = '';
				while( $offset > $this->nbytes_read ) {
					if( $this->readStreamDirectly( $offset - $this->nbytes_read ) === false ) {
						return false;
					}
				}
			}
			if( ( $_buf = $this->readStreamDirectly( $nbytes ) ) === false ) {
				return false;
			}
			$buf .= $_buf;
			return $buf;
		}
		function getHeaderInfo() {
			if( ( $buf = $this->readStreamDirectly( 12 ) ) === false ) {
					//$this = false;
				return;
			}
			return unpack( "nid/nspec/nqdcount/nancount/nnscount/narcount", $buf );
		}
		function getQueryRecords( $nrecs ) {
			$recs = array();
			while( --$nrecs >= 0 ) {
				if( ( $labels = $this->getLabels() ) === false ) {
					//$this = false;
					return;
				}
				if( ( $buf = $this->readStreamDirectly( 4 ) ) === false ) {
					//$this = false;
					return;
				}
				$info = unpack( "ntype/ndclass", $buf );

				$recs[] = new DNSRecord(
					new DNSName( $labels ),
					$info['type'],
					$info['dclass']
				);
			}
			return $recs;
		}
		function getResourceRecords( $nrecs ) {
			$recs = array();
			while( --$nrecs >= 0 ) {
				if( ( $labels = $this->getLabels() ) === false ) {
					return false;
				}
				if( ( $buf = $this->readStreamDirectly( 10 ) ) === false ) {
					return false;
				}
				$info = unpack( "ntype/ndclass/Nttl/nrdlength", $buf );
				switch( $info['type'] ) {
					case DNS_RECORDTYPE_CNAME:
					case DNS_RECORDTYPE_NS:
					case DNS_RECORDTYPE_PTR:
						if( ($_labels = $this->getLabels($info['rdlength']) ) === false ) {
							return false;
						}
						$specific_fields = array( 'dname' => new DNSName( $_labels ) );
						break;

					case DNS_RECORDTYPE_TXT:
						if( ($_labels = $this->getLabels($info['rdlength']) ) === false ) {
							return false;
						}
						$specific_fields = array( 'text' => $_labels );
						break;

					case DNS_RECORDTYPE_MX:
						if( ( $buf = $this->readStreamDirectly(2) ) === false ) {
							return false;
						}
						$specific_fields = unpack( 'npreference', $buf );
						if( ( $_labels = $this->getLabels($info['rdlength']-2) ) === false ) {
							return false;
						}
						$specific_fields['exchange'] = new DNSName( $_labels );
						break;

					case DNS_RECORDTYPE_A:
						if( ( $buf = $this->readStreamDirectly(4) ) === false ) {
							return false;
						}
						$specific_fields = array( 'address' => DNSName::newFromString( implode( '.', unpack( 'Ca/Cb/Cc/Cd', $buf ) ) ) );
						break;

					case DNS_RECORDTYPE_AAAA:
						if( ( $buf = $this->readStreamDirectly(16) ) === false ) {
							return false;
						}
						$specific_fields = array( 'address' => DNSName::newFromString( implode( '.', unpack( 'Ca/Cb/Cc/Cd/Ce/Cf/Cg/Ch/Ci/Cj/Ck/Cl/Cm/Cn/Co/Cp', $buf ) ).'.IP6.ARPA' ) );
						break;

					case DNS_RECORDTYPE_SOA:
						$specific_fields = array();
						if( ($_labels = $this->getLabels($info['rdlength']) ) === false ) {
							return false;
						}
						$specific_fields['source'] = new DNSName( $_labels );
						if( ($_labels = $this->getLabels($info['rdlength']) ) === false ) {
							return false;
						}
						$specific_fields['resp_person'] = array_shift( $_labels ).'@';
						$specific_fields['resp_person'] .= implode( '.', $_labels );

						if( ( $buf = $this->readStreamDirectly(20) ) === false ) {
							return false;
						}
						$specific_fields = array_merge(
							$specific_fields,
							unpack( 'Nserial/Nrefresh/Nretry/Nexpire/Nminttl', $buf )
						);
						break;

					default:
						if( $this->readStreamDirectly( $info['rdlength'] ) === false ) {
							return false;
						}
						$specific_fields = false;
				}

				$recs[] = new DNSRecord(
					new DNSName( $labels ),
					$info['type'],
					$info['dclass'],
					$info['ttl'],
					$specific_fields
				);
			}
			return $recs;
		}
		function getLabels( $max_length = 255, $offset = -1 ) {
			if( $offset < 0 ) { $offset = $this->nbytes_read; }
			$labels = array();
			for(;;) {
				if( --$max_length < 0 ) { return false; }
				if( ( $buf = $this->readBufferedStream( 1, $offset ) ) === false ) {
					return false;
				}
				$label_len = ord( $buf );
				++$offset;
				if( $label_len < 64 ) {
					/* uncompressed */
					if( ( $max_length -= $label_len ) < 0 ) { return false; }
					if( ( $labels[] = $this->readBufferedStream( $label_len, $offset ) ) === false ) {
						return false;
					}
					$offset += $label_len;
					if( $label_len == 0 ) { break; }
				} else {
					/* compressed */
					if( ( $buf = $this->readBufferedStream( 1, $offset ) ) === false ) {
						return false;
					}
					if( --$max_length < 0 ) {
						return false;
					}
					$_offset = ( ( $label_len & 0x3f ) << 8 ) + ord( $buf );

					if( ($_labels = $this->getLabels( $offset - $_offset, $_offset )) === false ) {
						return false;
					}
					$labels = array_merge( $labels, $_labels );
					break;
				}
			}
			return $labels;
		}
	}

	class	DNSAnswer
	{
		public $id;
		public $result_code;
		public $flags;
		public $rec_query;
		public $rec_answer;
		public $rec_authority;
		public $rec_additional;

		function DNSAnswer( &$stream, $limit ) {
			$msgparser = new DNSMessageParser( $stream, $limit );

			$info = & $msgparser->getHeaderInfo();

			$this->id = $info['id'];
			$this->result_code = $info['spec'] & DNS_HEADERSPEC_RESULT_CODE_MASK;
			$this->flags = $info['spec'] & DNS_HEADERSPEC_RESPONSE_SPEC_MASK;

			$nrec_query = $info['qdcount'];
			$nrec_answer = $info['ancount'];
			$nrec_authority = $info['nscount'];
			$nrec_additional = $info['arcount'];
			if( ( $this->rec_query = &$msgparser->getQueryRecords( $nrec_query ) ) === false ) {
				//$this = false;
				return;
			}

			if( ( $this->rec_answer = &$msgparser->getResourceRecords( $nrec_answer ) ) === false ) {
				//$this = false;
				return;
			}
			if( ( $this->rec_authority = &$msgparser->getResourceRecords( $nrec_authority ) ) === false ) {
				//$this = false;
				return;
			}

			if( ( $this->rec_additional = &$msgparser->getResourceRecords( $nrec_additional ) ) === false ) {
				//$this = false;
				return;
			}
		}
	}
	class	DNSName
	{
		public $labels;

		function DNSName( $labels ) {
			$this->labels = & $labels;
		}
		function isRealDomainName() {
			$i = count( $this->labels ) - 1;
			if( $i >= 1 && strtoupper($this->labels[$i-1]) == 'ARPA' ) {
				return false;
			}
			return true;
		}
		function newFromString( $domain_name ) {
			if( strpos( $domain_name, ':' ) !== false && !ereg( '[^0-9a-fA-f:.]', $domain_name ) ) {
				/* IPv6 address literal expression spec */
				$labels = array();
				$components = explode( ':', $domain_name );
				$ncomponents = count($components);
				$offset = $ncomponents;
				while( --$offset >= 0 ) {
					$subcomps = explode( '.', $components[$offset] );
					$nsubcomps = count( $subcomps );
					if( $nsubcomps == 1 ) {
						if( $subcomps[0] == '' ) {
							$_offset = 0;
							while( $components[$_offset] != '' ) {
								++$_offset;
							}
							$count = 9-($ncomponents-$offset)+$_offset;
							while( --$count >= 0 ) {
								$labels[] = '0';
								$labels[] = '0';
								$labels[] = '0';
								$labels[] = '0';
							}
							if( $_offset < $offset ) {
								$offset = $_offset;
							}
						} else {
							$compval = hexdec( $subcomps[0] );
							$labels[] = dechex( $compval & 0x0f );
							$compval >>= 4;
							$labels[] = dechex( $compval & 0x0f );
							$compval >>= 4;
							$labels[] = dechex( $compval & 0x0f );
							$compval >>= 4;
							$labels[] = dechex( $compval & 0x0f );
							$compval >>= 4;
						}
					} elseif( $nsubcomps == 4 ) {
						$labels[] = dechex( $subcomps[3] );
						$labels[] = dechex( $subcomps[2] );
						$labels[] = dechex( $subcomps[1] );
						$labels[] = dechex( $subcomps[0] );
					} else {
						return false;
					}
				}
				$labels[] = 'IP6';
				$labels[] = 'ARPA';
				$labels[] = '';
			} else {
				if( substr( $domain_name, -1, 1 ) != '.' ) {
					$domain_name .= '.';
				}
				$labels = explode( '.', $domain_name );
				$nlabels = count( $labels );
				if( $nlabels == 5 && !ereg( '[^0-9.]', $domain_name ) ) {
					/* IPv4 raw address literal representation spec */
					$tmp = (string)$labels[0];
					$labels[0] = (string)$labels[3];
					$labels[3] = $tmp;
					$tmp = (string)$labels[1];
					$labels[1] = (string)$labels[2];
					$labels[2] = $tmp;
					$labels[4] = 'IN-ADDR';
					$labels[5] = 'ARPA';
					$labels[6] = '';
				}
			}
			return new DNSName( $labels );
		}
		function asOctets() {
			$upto = count( $this->labels );
			$buf = '';
			for( $offset = 0; $offset < $upto; ++$offset ) {
				$label_len = strlen( $this->labels[$offset] );
				$buf .= pack( "C", $label_len ).$this->labels[$offset];
			}
			return $buf;
		}
		function getCanonicalName() {
			return implode( ".", $this->labels );
		}
	}
?>
