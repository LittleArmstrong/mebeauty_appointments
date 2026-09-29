# API Reference: URI.php

**Language**: PHP

**Source**: `system/core/URI.php`

---

## Classes

### CI_URI

**Inherits from**: (none)

#### Methods

##### __get(name: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |

**Returns**: `mixed`


##### __set(name: string, value: mixed) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |
| value | mixed | - | - |

**Returns**: `void`


##### __construct(config: CI_Config)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | CI_Config | - | - |


##### _set_uri_string(str, is_cli = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| is_cli | None | FALSE | - |


##### _parse_request_uri()


##### _parse_query_string()


##### _parse_argv()


##### _remove_relative_directory(uri)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| uri | None | - | - |


##### filter_uri(&$str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$str | None | - | - |


##### segment(n, no_result = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| no_result | None | NULL | - |


##### rsegment(n, no_result = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| no_result | None | NULL | - |


##### uri_to_assoc(n = 3, default = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 3 | - |
| default | None | array( | - |


##### ruri_to_assoc(n = 3, default = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 3 | - |
| default | None | array( | - |


##### _uri_to_assoc(n = 3, default = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 3 | - |
| default | None | array( | - |


##### assoc_to_uri(array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| array | None | - | - |


##### slash_segment(n, where = 'trailing')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| where | None | 'trailing' | - |


##### slash_rsegment(n, where = 'trailing')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| where | None | 'trailing' | - |


##### _slash_segment(n, where = 'trailing', which = 'segment')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| where | None | 'trailing' | - |
| which | None | 'segment' | - |


##### segment_array()


##### rsegment_array()


##### total_segments()


##### total_rsegments()


##### uri_string()


##### ruri_string()




## Functions

### __get(name: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |

**Returns**: `mixed`



### __set(name: string, value: mixed) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |
| value | mixed | - | - |

**Returns**: `void`



### __construct(config: CI_Config)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | CI_Config | - | - |

**Returns**: (none)



### _set_uri_string(str, is_cli = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| is_cli | None | FALSE | - |

**Returns**: (none)



### _parse_request_uri()

**Returns**: (none)



### _parse_query_string()

**Returns**: (none)



### _parse_argv()

**Returns**: (none)



### _remove_relative_directory(uri)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| uri | None | - | - |

**Returns**: (none)



### filter_uri(&$str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$str | None | - | - |

**Returns**: (none)



### segment(n, no_result = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| no_result | None | NULL | - |

**Returns**: (none)



### rsegment(n, no_result = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| no_result | None | NULL | - |

**Returns**: (none)



### uri_to_assoc(n = 3, default = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 3 | - |
| default | None | array( | - |

**Returns**: (none)



### ruri_to_assoc(n = 3, default = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 3 | - |
| default | None | array( | - |

**Returns**: (none)



### _uri_to_assoc(n = 3, default = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | 3 | - |
| default | None | array( | - |

**Returns**: (none)



### assoc_to_uri(array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| array | None | - | - |

**Returns**: (none)



### slash_segment(n, where = 'trailing')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| where | None | 'trailing' | - |

**Returns**: (none)



### slash_rsegment(n, where = 'trailing')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| where | None | 'trailing' | - |

**Returns**: (none)



### _slash_segment(n, where = 'trailing', which = 'segment')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |
| where | None | 'trailing' | - |
| which | None | 'segment' | - |

**Returns**: (none)



### segment_array()

**Returns**: (none)



### rsegment_array()

**Returns**: (none)



### total_segments()

**Returns**: (none)



### total_rsegments()

**Returns**: (none)



### uri_string()

**Returns**: (none)



### ruri_string()

**Returns**: (none)


