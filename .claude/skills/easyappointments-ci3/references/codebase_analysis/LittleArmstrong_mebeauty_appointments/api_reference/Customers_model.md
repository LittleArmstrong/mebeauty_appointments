# API Reference: Customers_model.php

**Language**: PHP

**Source**: `application/models/Customers_model.php`

---

## Classes

### Customers_model

**Inherits from**: EA_Model

#### Methods

##### save(customer: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `int`


##### validate(customer: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `void`


##### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### get_customer_role_id() → int

**Returns**: `int`


##### exists(customer: array) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `bool`


##### find_record_id(customer: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `int`


##### insert(customer: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `int`


##### update(customer: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `int`


##### delete(customer_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer_id | int | - | - |

**Returns**: `void`


##### find(customer_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer_id | int | - | - |

**Returns**: `array`


##### value(customer_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer_id | int | - | - |
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


##### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`


##### load(&$customer: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$customer | array | - | - |
| resources | array | - | - |


##### api_encode(&$customer: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$customer | array | - | - |

**Returns**: `void`


##### api_decode(&$customer: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$customer | array | - | - |
| base | ?array | null | - |

**Returns**: `void`




## Functions

### save(customer: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `int`



### validate(customer: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `void`



### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### get_customer_role_id() → int

**Returns**: `int`



### exists(customer: array) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `bool`



### find_record_id(customer: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `int`



### insert(customer: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `int`



### update(customer: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer | array | - | - |

**Returns**: `int`



### delete(customer_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer_id | int | - | - |

**Returns**: `void`



### find(customer_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer_id | int | - | - |

**Returns**: `array`



### value(customer_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| customer_id | int | - | - |
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



### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`



### load(&$customer: array, resources: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$customer | array | - | - |
| resources | array | - | - |

**Returns**: (none)



### api_encode(&$customer: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$customer | array | - | - |

**Returns**: `void`



### api_decode(&$customer: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$customer | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


