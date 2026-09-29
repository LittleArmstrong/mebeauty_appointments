# API Reference: EA_Model.php

**Language**: PHP

**Source**: `application/core/EA_Model.php`

---

## Classes

### EA_Model

**Inherits from**: CI_Model

#### Methods

##### __construct()


##### get_value(field: string, record_id: int) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | string | - | - |
| record_id | int | - | - |

**Returns**: `string`


##### get_row(record_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| record_id | int | - | - |

**Returns**: `array`


##### get_batch(where = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | None | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### add(record: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| record | array | - | - |

**Returns**: `int`


##### cast(&$record: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$record | array | - | - |


##### only(&$record: array, fields: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$record | array | - | - |
| fields | array | - | - |


##### optional(&$record: array, fields: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$record | array | - | - |
| fields | array | - | - |


##### db_field(api_field: string) → ?string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| api_field | string | - | - |

**Returns**: `?string`


##### quote_order_by(order_by: ?string) → ?string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| order_by | ?string | - | - |

**Returns**: `?string`




## Functions

### __construct()

**Returns**: (none)



### get_value(field: string, record_id: int) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | string | - | - |
| record_id | int | - | - |

**Returns**: `string`



### get_row(record_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| record_id | int | - | - |

**Returns**: `array`



### get_batch(where = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | None | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### add(record: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| record | array | - | - |

**Returns**: `int`



### cast(&$record: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$record | array | - | - |

**Returns**: (none)



### only(&$record: array, fields: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$record | array | - | - |
| fields | array | - | - |

**Returns**: (none)



### optional(&$record: array, fields: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$record | array | - | - |
| fields | array | - | - |

**Returns**: (none)



### db_field(api_field: string) → ?string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| api_field | string | - | - |

**Returns**: `?string`



### quote_order_by(order_by: ?string) → ?string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| order_by | ?string | - | - |

**Returns**: `?string`


