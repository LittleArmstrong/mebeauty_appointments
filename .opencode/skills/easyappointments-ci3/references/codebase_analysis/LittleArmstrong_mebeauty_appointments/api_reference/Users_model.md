# API Reference: Users_model.php

**Language**: PHP

**Source**: `application/models/Users_model.php`

---

## Classes

### Users_model

**Inherits from**: EA_Model

#### Methods

##### save(user: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user | array | - | - |

**Returns**: `int`


##### validate(user: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user | array | - | - |

**Returns**: `void`


##### insert(user: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user | array | - | - |

**Returns**: `int`


##### set_settings(user_id: int, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |
| settings | array | - | - |

**Returns**: `void`


##### set_setting(user_id: int, name: string, value: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |
| name | string | - | - |
| value | string | - | - |

**Returns**: `void`


##### update(user: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user | array | - | - |

**Returns**: `int`


##### delete(user_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |

**Returns**: `void`


##### find(user_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |

**Returns**: `array`


##### get_settings(user_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |

**Returns**: `array`


##### value(user_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`


##### get_setting(user_id: int, name: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |
| name | string | - | - |

**Returns**: `string`


##### query() → CI_DB_query_builder

**Returns**: `CI_DB_query_builder`


##### search(keyword: string, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| keyword | string | - | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

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


##### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`


##### load(&$user: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$user | array | - | - |
| resources | array | - | - |


##### validate_username(username: string, user_id: ?int = null) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| user_id | ?int | null | - |

**Returns**: `bool`




## Functions

### save(user: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user | array | - | - |

**Returns**: `int`



### validate(user: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user | array | - | - |

**Returns**: `void`



### insert(user: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user | array | - | - |

**Returns**: `int`



### set_settings(user_id: int, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |
| settings | array | - | - |

**Returns**: `void`



### set_setting(user_id: int, name: string, value: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |
| name | string | - | - |
| value | string | - | - |

**Returns**: `void`



### update(user: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user | array | - | - |

**Returns**: `int`



### delete(user_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |

**Returns**: `void`



### find(user_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |

**Returns**: `array`



### get_settings(user_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |

**Returns**: `array`



### value(user_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`



### get_setting(user_id: int, name: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| user_id | int | - | - |
| name | string | - | - |

**Returns**: `string`



### query() → CI_DB_query_builder

**Returns**: `CI_DB_query_builder`



### search(keyword: string, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| keyword | string | - | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

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



### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`



### load(&$user: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$user | array | - | - |
| resources | array | - | - |

**Returns**: (none)



### validate_username(username: string, user_id: ?int = null) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| user_id | ?int | null | - |

**Returns**: `bool`


