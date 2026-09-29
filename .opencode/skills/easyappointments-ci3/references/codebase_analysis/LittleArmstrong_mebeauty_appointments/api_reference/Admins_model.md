# API Reference: Admins_model.php

**Language**: PHP

**Source**: `application/models/Admins_model.php`

---

## Classes

### Admins_model

**Inherits from**: EA_Model

#### Methods

##### save(admin: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin | array | - | - |

**Returns**: `int`


##### validate(admin: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin | array | - | - |

**Returns**: `void`


##### validate_username(username: string, admin_id: ?int = null) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| admin_id | ?int | null | - |

**Returns**: `bool`


##### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### get_admin_role_id() → int

**Returns**: `int`


##### get_settings(admin_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |

**Returns**: `array`


##### insert(admin: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin | array | - | - |

**Returns**: `int`


##### set_settings(admin_id: int, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |
| settings | array | - | - |

**Returns**: `void`


##### set_setting(admin_id: int, name: string, value: mixed = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |
| name | string | - | - |
| value | mixed | null | - |

**Returns**: `void`


##### update(admin: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin | array | - | - |

**Returns**: `int`


##### delete(admin_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |

**Returns**: `void`


##### find(admin_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |

**Returns**: `array`


##### value(admin_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`


##### get_setting(admin_id: int, name: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |
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


##### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`


##### load(&$admin: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$admin | array | - | - |
| resources | array | - | - |


##### api_encode(&$admin: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$admin | array | - | - |

**Returns**: `void`


##### api_decode(&$admin: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$admin | array | - | - |
| base | ?array | null | - |

**Returns**: `void`




## Functions

### save(admin: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin | array | - | - |

**Returns**: `int`



### validate(admin: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin | array | - | - |

**Returns**: `void`



### validate_username(username: string, admin_id: ?int = null) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| admin_id | ?int | null | - |

**Returns**: `bool`



### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### get_admin_role_id() → int

**Returns**: `int`



### get_settings(admin_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |

**Returns**: `array`



### insert(admin: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin | array | - | - |

**Returns**: `int`



### set_settings(admin_id: int, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |
| settings | array | - | - |

**Returns**: `void`



### set_setting(admin_id: int, name: string, value: mixed = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |
| name | string | - | - |
| value | mixed | null | - |

**Returns**: `void`



### update(admin: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin | array | - | - |

**Returns**: `int`



### delete(admin_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |

**Returns**: `void`



### find(admin_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |

**Returns**: `array`



### value(admin_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`



### get_setting(admin_id: int, name: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| admin_id | int | - | - |
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



### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`



### load(&$admin: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$admin | array | - | - |
| resources | array | - | - |

**Returns**: (none)



### api_encode(&$admin: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$admin | array | - | - |

**Returns**: `void`



### api_decode(&$admin: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$admin | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


