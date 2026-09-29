# API Reference: Encryption.php

**Language**: PHP

**Source**: `system/libraries/Encryption.php`

---

## Classes

### CI_Encryption

**Inherits from**: (none)

#### Methods

##### __construct(params: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | array | array( | - |


##### initialize(params: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | array | - | - |


##### _mcrypt_initialize(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |


##### _openssl_initialize(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |


##### create_key(length)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| length | None | - | - |


##### encrypt(data, params: array = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | array | NULL | - |


##### _mcrypt_encrypt(data, params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | None | - | - |


##### _openssl_encrypt(data, params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | None | - | - |


##### decrypt(data, params: array = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | array | NULL | - |


##### _mcrypt_decrypt(data, params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | None | - | - |


##### _openssl_decrypt(data, params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | None | - | - |


##### _get_params(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |


##### _mcrypt_get_handle(cipher, mode)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| cipher | None | - | - |
| mode | None | - | - |


##### _openssl_get_handle(cipher, mode)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| cipher | None | - | - |
| mode | None | - | - |


##### _cipher_alias(&$cipher)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$cipher | None | - | - |


##### hkdf(key, digest = 'sha512', salt = NULL, length = NULL, info = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| digest | None | 'sha512' | - |
| salt | None | NULL | - |
| length | None | NULL | - |
| info | None | '' | - |


##### __get(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### strlen(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### substr(str, start, length = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| start | None | - | - |
| length | None | NULL | - |




## Functions

### __construct(params: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | array | array( | - |

**Returns**: (none)



### initialize(params: array)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | array | - | - |

**Returns**: (none)



### _mcrypt_initialize(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |

**Returns**: (none)



### _openssl_initialize(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |

**Returns**: (none)



### create_key(length)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| length | None | - | - |

**Returns**: (none)



### encrypt(data, params: array = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | array | NULL | - |

**Returns**: (none)



### _mcrypt_encrypt(data, params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | None | - | - |

**Returns**: (none)



### _openssl_encrypt(data, params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | None | - | - |

**Returns**: (none)



### decrypt(data, params: array = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | array | NULL | - |

**Returns**: (none)



### _mcrypt_decrypt(data, params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | None | - | - |

**Returns**: (none)



### _openssl_decrypt(data, params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | - | - |
| params | None | - | - |

**Returns**: (none)



### _get_params(params)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | - | - |

**Returns**: (none)



### _mcrypt_get_handle(cipher, mode)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| cipher | None | - | - |
| mode | None | - | - |

**Returns**: (none)



### _openssl_get_handle(cipher, mode)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| cipher | None | - | - |
| mode | None | - | - |

**Returns**: (none)



### _cipher_alias(&$cipher)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$cipher | None | - | - |

**Returns**: (none)



### hkdf(key, digest = 'sha512', salt = NULL, length = NULL, info = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |
| digest | None | 'sha512' | - |
| salt | None | NULL | - |
| length | None | NULL | - |
| info | None | '' | - |

**Returns**: (none)



### __get(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### strlen(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### substr(str, start, length = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| start | None | - | - |
| length | None | NULL | - |

**Returns**: (none)


