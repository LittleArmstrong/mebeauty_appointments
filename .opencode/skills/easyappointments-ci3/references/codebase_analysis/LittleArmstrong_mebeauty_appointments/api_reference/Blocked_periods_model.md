# API Reference: Blocked_periods_model.php

**Language**: PHP

**Source**: `application/models/Blocked_periods_model.php`

---

## Classes

### Blocked_periods_model

**Inherits from**: EA_Model

#### Methods

##### save(blocked_period: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period | array | - | - |

**Returns**: `int`


##### validate(blocked_period: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period | array | - | - |

**Returns**: `void`


##### insert(blocked_period: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period | array | - | - |

**Returns**: `int`


##### update(blocked_period: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period | array | - | - |

**Returns**: `int`


##### delete(blocked_period_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period_id | int | - | - |

**Returns**: `void`


##### find(blocked_period_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period_id | int | - | - |

**Returns**: `array`


##### value(blocked_period_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`


##### search(keyword: string, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| keyword | string | - | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`


##### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### load(&$blocked_period: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$blocked_period | array | - | - |
| resources | array | - | - |


##### api_encode(&$blocked_period: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$blocked_period | array | - | - |

**Returns**: `void`


##### api_decode(&$blocked_period: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$blocked_period | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


##### get_for_period(start_date: string, end_date: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| start_date | string | - | - |
| end_date | string | - | - |

**Returns**: `array`


##### query() → CI_DB_query_builder

**Returns**: `CI_DB_query_builder`


##### is_entire_date_blocked(date: string) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |

**Returns**: `bool`




## Functions

### save(blocked_period: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period | array | - | - |

**Returns**: `int`



### validate(blocked_period: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period | array | - | - |

**Returns**: `void`



### insert(blocked_period: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period | array | - | - |

**Returns**: `int`



### update(blocked_period: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period | array | - | - |

**Returns**: `int`



### delete(blocked_period_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period_id | int | - | - |

**Returns**: `void`



### find(blocked_period_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period_id | int | - | - |

**Returns**: `array`



### value(blocked_period_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| blocked_period_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`



### search(keyword: string, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| keyword | string | - | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`



### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### load(&$blocked_period: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$blocked_period | array | - | - |
| resources | array | - | - |

**Returns**: (none)



### api_encode(&$blocked_period: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$blocked_period | array | - | - |

**Returns**: `void`



### api_decode(&$blocked_period: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$blocked_period | array | - | - |
| base | ?array | null | - |

**Returns**: `void`



### get_for_period(start_date: string, end_date: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| start_date | string | - | - |
| end_date | string | - | - |

**Returns**: `array`



### query() → CI_DB_query_builder

**Returns**: `CI_DB_query_builder`



### is_entire_date_blocked(date: string) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |

**Returns**: `bool`


