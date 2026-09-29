# API Reference: Xmlrpc.php

**Language**: PHP

**Source**: `system/libraries/Xmlrpc.php`

---

## Classes

### CI_Xmlrpc

**Inherits from**: (none)

#### Methods

##### __construct(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |


##### initialize(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |


##### server(url, port = 80, proxy = FALSE, proxy_port = 8080)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| url | None | - | - |
| port | None | 80 | - |
| proxy | None | FALSE | - |
| proxy_port | None | 8080 | - |


##### timeout(seconds = 5)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| seconds | None | 5 | - |


##### method(function)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| function | None | - | - |


##### request(incoming)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| incoming | None | - | - |


##### set_debug(flag = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| flag | None | TRUE | - |


##### values_parsing(value)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| value | None | - | - |


##### send_request()


##### display_error()


##### display_response()


##### send_error_message(number, message)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| number | None | - | - |
| message | None | - | - |


##### send_response(response)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| response | None | - | - |




### XML_RPC_Client

**Inherits from**: CI_Xmlrpc

#### Methods

##### __construct(path, server, port = 80, proxy = FALSE, proxy_port = 8080)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| server | None | - | - |
| port | None | 80 | - |
| proxy | None | FALSE | - |
| proxy_port | None | 8080 | - |


##### send(msg)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |


##### sendPayload(msg)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |




### XML_RPC_Response

**Inherits from**: (none)

#### Methods

##### __construct(val, code = 0, fstr = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| val | None | - | - |
| code | None | 0 | - |
| fstr | None | '' | - |


##### faultCode()


##### faultString()


##### value()


##### prepare_response()


##### decode(array = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| array | None | NULL | - |


##### xmlrpc_decoder(xmlrpc_val)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| xmlrpc_val | None | - | - |


##### iso8601_decode(time, utc = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| time | None | - | - |
| utc | None | FALSE | - |




### XML_RPC_Message

**Inherits from**: CI_Xmlrpc

#### Methods

##### __construct(method, pars = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| method | None | - | - |
| pars | None | FALSE | - |


##### createPayload()


##### parseResponse(fp)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| fp | None | - | - |


##### open_tag(the_parser, name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| the_parser | None | - | - |
| name | None | - | - |


##### closing_tag(the_parser, name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| the_parser | None | - | - |
| name | None | - | - |


##### character_data(the_parser, data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| the_parser | None | - | - |
| data | None | - | - |


##### addParam(par)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| par | None | - | - |


##### output_parameters(array: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| array | array | array( | - |


##### decode_message(param)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| param | None | - | - |




### XML_RPC_Values

**Inherits from**: CI_Xmlrpc

#### Methods

##### __construct(val = -1, type = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| val | None | -1 | - |
| type | None | '' | - |


##### addScalar(val, type = 'string')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| val | None | - | - |
| type | None | 'string' | - |


##### addArray(vals)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vals | None | - | - |


##### addStruct(vals)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vals | None | - | - |


##### kindOf()


##### serializedata(typ, val)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| typ | None | - | - |
| val | None | - | - |


##### serialize_class()


##### serializeval(o)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| o | None | - | - |


##### scalarval()


##### iso8601_encode(time, utc = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| time | None | - | - |
| utc | None | FALSE | - |




## Functions

### __construct(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |

**Returns**: (none)



### initialize(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |

**Returns**: (none)



### server(url, port = 80, proxy = FALSE, proxy_port = 8080)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| url | None | - | - |
| port | None | 80 | - |
| proxy | None | FALSE | - |
| proxy_port | None | 8080 | - |

**Returns**: (none)



### timeout(seconds = 5)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| seconds | None | 5 | - |

**Returns**: (none)



### method(function)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| function | None | - | - |

**Returns**: (none)



### request(incoming)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| incoming | None | - | - |

**Returns**: (none)



### set_debug(flag = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| flag | None | TRUE | - |

**Returns**: (none)



### values_parsing(value)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| value | None | - | - |

**Returns**: (none)



### send_request()

**Returns**: (none)



### display_error()

**Returns**: (none)



### display_response()

**Returns**: (none)



### send_error_message(number, message)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| number | None | - | - |
| message | None | - | - |

**Returns**: (none)



### send_response(response)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| response | None | - | - |

**Returns**: (none)



### __construct(path, server, port = 80, proxy = FALSE, proxy_port = 8080)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| server | None | - | - |
| port | None | 80 | - |
| proxy | None | FALSE | - |
| proxy_port | None | 8080 | - |

**Returns**: (none)



### send(msg)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |

**Returns**: (none)



### sendPayload(msg)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |

**Returns**: (none)



### __construct(val, code = 0, fstr = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| val | None | - | - |
| code | None | 0 | - |
| fstr | None | '' | - |

**Returns**: (none)



### faultCode()

**Returns**: (none)



### faultString()

**Returns**: (none)



### value()

**Returns**: (none)



### prepare_response()

**Returns**: (none)



### decode(array = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| array | None | NULL | - |

**Returns**: (none)



### xmlrpc_decoder(xmlrpc_val)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| xmlrpc_val | None | - | - |

**Returns**: (none)



### iso8601_decode(time, utc = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| time | None | - | - |
| utc | None | FALSE | - |

**Returns**: (none)



### __construct(method, pars = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| method | None | - | - |
| pars | None | FALSE | - |

**Returns**: (none)



### createPayload()

**Returns**: (none)



### parseResponse(fp)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| fp | None | - | - |

**Returns**: (none)



### open_tag(the_parser, name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| the_parser | None | - | - |
| name | None | - | - |

**Returns**: (none)



### closing_tag(the_parser, name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| the_parser | None | - | - |
| name | None | - | - |

**Returns**: (none)



### character_data(the_parser, data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| the_parser | None | - | - |
| data | None | - | - |

**Returns**: (none)



### addParam(par)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| par | None | - | - |

**Returns**: (none)



### output_parameters(array: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| array | array | array( | - |

**Returns**: (none)



### decode_message(param)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| param | None | - | - |

**Returns**: (none)



### __construct(val = -1, type = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| val | None | -1 | - |
| type | None | '' | - |

**Returns**: (none)



### addScalar(val, type = 'string')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| val | None | - | - |
| type | None | 'string' | - |

**Returns**: (none)



### addArray(vals)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vals | None | - | - |

**Returns**: (none)



### addStruct(vals)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vals | None | - | - |

**Returns**: (none)



### kindOf()

**Returns**: (none)



### serializedata(typ, val)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| typ | None | - | - |
| val | None | - | - |

**Returns**: (none)



### serialize_class()

**Returns**: (none)



### serializeval(o)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| o | None | - | - |

**Returns**: (none)



### scalarval()

**Returns**: (none)



### iso8601_encode(time, utc = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| time | None | - | - |
| utc | None | FALSE | - |

**Returns**: (none)


