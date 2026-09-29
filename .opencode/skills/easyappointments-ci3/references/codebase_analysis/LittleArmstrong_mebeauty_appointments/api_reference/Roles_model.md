# API Reference: Roles_model.php

**Language**: PHP

**Source**: `application/models/Roles_model.php`

---

## Classes

### Roles_model

**Inherits from**: EA_Model

#### Methods

##### save(role: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role | array | - | - |

**Returns**: `int`


##### validate(role: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role | array | - | - |

**Returns**: `void`


##### insert(role: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role | array | - | - |

**Returns**: `int`


##### update(role: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role | array | - | - |

**Returns**: `int`


##### delete(role_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role_id | int | - | - |

**Returns**: `void`


##### find(role_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role_id | int | - | - |

**Returns**: `array`


##### value(role_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`


##### get_permissions_by_slug(slug: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| slug | string | - | - |

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


##### load(&$role: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$role | array | - | - |
| resources | array | - | - |




## Functions

### save(role: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role | array | - | - |

**Returns**: `int`



### validate(role: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role | array | - | - |

**Returns**: `void`



### insert(role: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role | array | - | - |

**Returns**: `int`



### update(role: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role | array | - | - |

**Returns**: `int`



### delete(role_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role_id | int | - | - |

**Returns**: `void`



### find(role_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role_id | int | - | - |

**Returns**: `array`



### value(role_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| role_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`



### get_permissions_by_slug(slug: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| slug | string | - | - |

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



### load(&$role: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$role | array | - | - |
| resources | array | - | - |

**Returns**: (none)


