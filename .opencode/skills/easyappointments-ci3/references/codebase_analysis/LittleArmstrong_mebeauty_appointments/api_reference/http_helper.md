# API Reference: http_helper.php

**Language**: PHP

**Source**: `application/helpers/http_helper.php`

---

## Functions

### request(key: ?string = null, default = null) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | ?string | null | - |
| default | None | null | - |

**Returns**: `mixed`



### response(content: string = '', status: int = 200, headers: array = []) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| content | string | '' | - |
| status | int | 200 | - |
| headers | array | [] | - |

**Returns**: `void`



### response(content: string = '', status: int = 200, headers: array = []) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| content | string | '' | - |
| status | int | 200 | - |
| headers | array | [] | - |

**Returns**: `void`



### json_response(content: array = [], status: int = 200, headers: array = []) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| content | array | [] | - |
| status | int | 200 | - |
| headers | array | [] | - |

**Returns**: `void`



### json_exception(e: Throwable) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| e | Throwable | - | - |

**Returns**: `void`



### abort(code: int, message: string = '', headers: array = []) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| code | int | - | - |
| message | string | '' | - |
| headers | array | [] | - |

**Returns**: `void`



### trace(e: Throwable) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| e | Throwable | - | - |

**Returns**: `string`



### method(expected_method: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| expected_method | string | - | - |

**Returns**: `void`



### check(key: string, types: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | string | - | - |
| types | string | - | - |

**Returns**: `void`


