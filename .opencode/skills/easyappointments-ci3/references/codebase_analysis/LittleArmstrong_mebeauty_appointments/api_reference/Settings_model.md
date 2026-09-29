# API Reference: Settings_model.php

**Language**: PHP

**Source**: `application/models/Settings_model.php`

---

## Classes

### Settings_model

**Inherits from**: EA_Model

#### Methods

##### save(setting: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting | array | - | - |

**Returns**: `int`


##### validate(setting: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting | array | - | - |

**Returns**: `void`


##### insert(setting: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting | array | - | - |

**Returns**: `int`


##### update(setting: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting | array | - | - |

**Returns**: `int`


##### delete(setting_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting_id | int | - | - |

**Returns**: `void`


##### find(setting_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting_id | int | - | - |

**Returns**: `array`


##### value(setting_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`


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


##### load(&$setting: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$setting | array | - | - |
| resources | array | - | - |


##### api_encode(&$setting: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$setting | array | - | - |

**Returns**: `void`


##### api_decode(&$setting: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$setting | array | - | - |
| base | ?array | null | - |

**Returns**: `void`




## Functions

### save(setting: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting | array | - | - |

**Returns**: `int`



### validate(setting: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting | array | - | - |

**Returns**: `void`



### insert(setting: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting | array | - | - |

**Returns**: `int`



### update(setting: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting | array | - | - |

**Returns**: `int`



### delete(setting_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting_id | int | - | - |

**Returns**: `void`



### find(setting_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting_id | int | - | - |

**Returns**: `array`



### value(setting_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| setting_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`



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



### load(&$setting: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$setting | array | - | - |
| resources | array | - | - |

**Returns**: (none)



### api_encode(&$setting: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$setting | array | - | - |

**Returns**: `void`



### api_decode(&$setting: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$setting | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


