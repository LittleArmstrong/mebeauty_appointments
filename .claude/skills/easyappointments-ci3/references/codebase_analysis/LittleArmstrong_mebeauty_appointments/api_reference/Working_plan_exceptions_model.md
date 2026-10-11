# API Reference: Working_plan_exceptions_model.php

**Language**: PHP

**Source**: `application/models/Working_plan_exceptions_model.php`

---

## Classes

### Working_plan_exceptions_model

**Inherits from**: EA_Model

#### Methods

##### save(working_plan_exception: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception | array | - | - |

**Returns**: `int`


##### validate(working_plan_exception: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception | array | - | - |

**Returns**: `void`


##### insert(working_plan_exception: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception | array | - | - |

**Returns**: `int`


##### update(working_plan_exception: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception | array | - | - |

**Returns**: `int`


##### delete(working_plan_exception_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception_id | int | - | - |

**Returns**: `void`


##### find(working_plan_exception_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception_id | int | - | - |

**Returns**: `array`


##### value(working_plan_exception_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception_id | int | - | - |
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


##### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### load(&$working_plan_exception: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$working_plan_exception | array | - | - |
| resources | array | - | - |

**Returns**: `void`


##### api_encode(&$working_plan_exception: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$working_plan_exception | array | - | - |

**Returns**: `void`


##### api_decode(&$working_plan_exception: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$working_plan_exception | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


##### get_for_period(provider_id: int, start_date: string, end_date: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| start_date | string | - | - |
| end_date | string | - | - |

**Returns**: `array`


##### get_by_provider(provider_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `array`


##### get_all_by_provider(provider_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `array`


##### query() → CI_DB_query_builder

**Returns**: `CI_DB_query_builder`


##### find_by_provider_and_date(provider_id: int, date: string) → ?array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| date | string | - | - |

**Returns**: `?array`


##### delete_by_provider_and_date(provider_id: int, date: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| date | string | - | - |

**Returns**: `void`




## Functions

### save(working_plan_exception: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception | array | - | - |

**Returns**: `int`



### validate(working_plan_exception: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception | array | - | - |

**Returns**: `void`



### insert(working_plan_exception: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception | array | - | - |

**Returns**: `int`



### update(working_plan_exception: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception | array | - | - |

**Returns**: `int`



### delete(working_plan_exception_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception_id | int | - | - |

**Returns**: `void`



### find(working_plan_exception_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception_id | int | - | - |

**Returns**: `array`



### value(working_plan_exception_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| working_plan_exception_id | int | - | - |
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



### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### load(&$working_plan_exception: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$working_plan_exception | array | - | - |
| resources | array | - | - |

**Returns**: `void`



### api_encode(&$working_plan_exception: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$working_plan_exception | array | - | - |

**Returns**: `void`



### api_decode(&$working_plan_exception: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$working_plan_exception | array | - | - |
| base | ?array | null | - |

**Returns**: `void`



### get_for_period(provider_id: int, start_date: string, end_date: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| start_date | string | - | - |
| end_date | string | - | - |

**Returns**: `array`



### get_by_provider(provider_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `array`



### get_all_by_provider(provider_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `array`



### query() → CI_DB_query_builder

**Returns**: `CI_DB_query_builder`



### find_by_provider_and_date(provider_id: int, date: string) → ?array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| date | string | - | - |

**Returns**: `?array`



### delete_by_provider_and_date(provider_id: int, date: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| date | string | - | - |

**Returns**: `void`


