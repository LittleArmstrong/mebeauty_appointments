# API Reference: Providers_model.php

**Language**: PHP

**Source**: `application/models/Providers_model.php`

---

## Classes

### Providers_model

**Inherits from**: EA_Model

#### Methods

##### save(provider: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |

**Returns**: `int`


##### validate(provider: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |

**Returns**: `void`


##### validate_username(username: string, provider_id: ?int = null) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| provider_id | ?int | null | - |

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


##### get_provider_role_id() → int

**Returns**: `int`


##### get_settings(provider_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `array`


##### get_service_ids(provider_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `array`


##### insert(provider: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |

**Returns**: `int`


##### set_settings(provider_id: int, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| settings | array | - | - |

**Returns**: `void`


##### set_setting(provider_id: int, name: string, value: mixed = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| name | string | - | - |
| value | mixed | null | - |

**Returns**: `void`


##### update(provider: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |

**Returns**: `int`


##### set_service_ids(provider_id: int, service_ids: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| service_ids | array | - | - |

**Returns**: `void`


##### delete(provider_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `void`


##### value(provider_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`


##### get_setting(provider_id: int, name: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| name | string | - | - |

**Returns**: `string`


##### save_working_plan_exception(provider_id: int, working_plan_exception: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| working_plan_exception | array | - | - |

**Returns**: `int`


##### find(provider_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `array`


##### delete_working_plan_exception(provider_id: int, date: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| date | string | - | - |

**Returns**: `void`


##### get_available_providers(without_private: bool = false) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| without_private | bool | false | - |

**Returns**: `array`


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


##### load(&$provider: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$provider | array | - | - |
| resources | array | - | - |

**Returns**: `void`


##### api_encode(&$provider: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$provider | array | - | - |

**Returns**: `void`


##### api_decode(&$provider: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$provider | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


##### is_service_supported(provider_id: int, service_id: int) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| service_id | int | - | - |

**Returns**: `bool`




## Functions

### save(provider: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |

**Returns**: `int`



### validate(provider: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |

**Returns**: `void`



### validate_username(username: string, provider_id: ?int = null) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| username | string | - | - |
| provider_id | ?int | null | - |

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



### get_provider_role_id() → int

**Returns**: `int`



### get_settings(provider_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `array`



### get_service_ids(provider_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `array`



### insert(provider: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |

**Returns**: `int`



### set_settings(provider_id: int, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| settings | array | - | - |

**Returns**: `void`



### set_setting(provider_id: int, name: string, value: mixed = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| name | string | - | - |
| value | mixed | null | - |

**Returns**: `void`



### update(provider: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |

**Returns**: `int`



### set_service_ids(provider_id: int, service_ids: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| service_ids | array | - | - |

**Returns**: `void`



### delete(provider_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `void`



### value(provider_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`



### get_setting(provider_id: int, name: string) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| name | string | - | - |

**Returns**: `string`



### save_working_plan_exception(provider_id: int, working_plan_exception: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| working_plan_exception | array | - | - |

**Returns**: `int`



### find(provider_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `array`



### delete_working_plan_exception(provider_id: int, date: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| date | string | - | - |

**Returns**: `void`



### get_available_providers(without_private: bool = false) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| without_private | bool | false | - |

**Returns**: `array`



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



### load(&$provider: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$provider | array | - | - |
| resources | array | - | - |

**Returns**: `void`



### api_encode(&$provider: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$provider | array | - | - |

**Returns**: `void`



### api_decode(&$provider: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$provider | array | - | - |
| base | ?array | null | - |

**Returns**: `void`



### is_service_supported(provider_id: int, service_id: int) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| service_id | int | - | - |

**Returns**: `bool`


