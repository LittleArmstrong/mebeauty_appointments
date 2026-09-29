# API Reference: Secretaries_model.php

**Language**: PHP

**Source**: `application/models/Secretaries_model.php`

---

## Classes

### Secretaries_model

**Inherits from**: EA_Model

#### Methods

##### save(secretary: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary | array | - | - |

**Returns**: `int`


##### validate(secretary: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary | array | - | - |

**Returns**: `void`


##### validate_username(username: string, secretary_id: ?int = null) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| secretary_id | ?int | null | - |

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


##### get_secretary_role_id() → int

**Returns**: `int`


##### get_settings(secretary_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |

**Returns**: `array`


##### get_provider_ids(secretary_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |

**Returns**: `array`


##### insert(secretary: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary | array | - | - |

**Returns**: `int`


##### set_settings(secretary_id: int, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
| settings | array | - | - |

**Returns**: `void`


##### set_setting(secretary_id: int, name: string, value: mixed = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
| name | string | - | - |
| value | mixed | null | - |

**Returns**: `void`


##### update(secretary: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary | array | - | - |

**Returns**: `int`


##### set_provider_ids(secretary_id: int, provider_ids: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
| provider_ids | array | - | - |

**Returns**: `void`


##### delete(secretary_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |

**Returns**: `void`


##### value(secretary_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`


##### get_setting(secretary_id: int, name: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
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


##### load(&$secretary: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$secretary | array | - | - |
| resources | array | - | - |

**Returns**: `void`


##### api_encode(&$secretary: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$secretary | array | - | - |

**Returns**: `void`


##### api_decode(&$secretary: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$secretary | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


##### is_provider_supported(secretary_id: int, provider_id: int) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
| provider_id | int | - | - |

**Returns**: `bool`


##### find(secretary_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |

**Returns**: `array`




## Functions

### save(secretary: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary | array | - | - |

**Returns**: `int`



### validate(secretary: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary | array | - | - |

**Returns**: `void`



### validate_username(username: string, secretary_id: ?int = null) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| secretary_id | ?int | null | - |

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



### get_secretary_role_id() → int

**Returns**: `int`



### get_settings(secretary_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |

**Returns**: `array`



### get_provider_ids(secretary_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |

**Returns**: `array`



### insert(secretary: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary | array | - | - |

**Returns**: `int`



### set_settings(secretary_id: int, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
| settings | array | - | - |

**Returns**: `void`



### set_setting(secretary_id: int, name: string, value: mixed = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
| name | string | - | - |
| value | mixed | null | - |

**Returns**: `void`



### update(secretary: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary | array | - | - |

**Returns**: `int`



### set_provider_ids(secretary_id: int, provider_ids: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
| provider_ids | array | - | - |

**Returns**: `void`



### delete(secretary_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |

**Returns**: `void`



### value(secretary_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`



### get_setting(secretary_id: int, name: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
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



### load(&$secretary: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$secretary | array | - | - |
| resources | array | - | - |

**Returns**: `void`



### api_encode(&$secretary: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$secretary | array | - | - |

**Returns**: `void`



### api_decode(&$secretary: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$secretary | array | - | - |
| base | ?array | null | - |

**Returns**: `void`



### is_provider_supported(secretary_id: int, provider_id: int) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |
| provider_id | int | - | - |

**Returns**: `bool`



### find(secretary_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| secretary_id | int | - | - |

**Returns**: `array`


