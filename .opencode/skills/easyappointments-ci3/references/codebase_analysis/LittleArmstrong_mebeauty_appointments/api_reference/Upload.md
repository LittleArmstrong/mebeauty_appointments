# API Reference: Upload.php

**Language**: PHP

**Source**: `system/libraries/Upload.php`

---

## Classes

### CI_Upload

**Inherits from**: (none)

#### Methods

##### __construct(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |


##### initialize(config: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | array | array( | - |


##### do_upload(field = 'userfile')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | 'userfile' | - |


##### data(index = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | NULL | - |


##### set_upload_path(path)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |


##### set_filename(path, filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| filename | None | - | - |


##### set_max_filesize(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |


##### set_max_size(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |


##### set_max_filename(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |


##### set_max_width(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |


##### set_max_height(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |


##### set_min_width(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |


##### set_min_height(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |


##### set_allowed_types(types)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| types | None | - | - |


##### set_image_properties(path = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '' | - |


##### set_xss_clean(flag = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| flag | None | FALSE | - |


##### is_image()


##### is_allowed_filetype(ignore_mime = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ignore_mime | None | FALSE | - |


##### is_allowed_filesize()


##### is_allowed_dimensions()


##### validate_upload_path()


##### get_extension(filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |


##### limit_filename_length(filename, length)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |
| length | None | - | - |


##### do_xss_clean()


##### set_error(msg, log_level = 'error')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |
| log_level | None | 'error' | - |


##### display_errors(open = '<p>', close = '</p>')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| open | None | '<p>' | - |
| close | None | '</p>' | - |


##### _prep_filename(filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |


##### _file_mime_type(file)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| file | None | - | - |




## Functions

### __construct(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |

**Returns**: (none)



### initialize(config: array = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | array | array( | - |

**Returns**: (none)



### do_upload(field = 'userfile')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | 'userfile' | - |

**Returns**: (none)



### data(index = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| index | None | NULL | - |

**Returns**: (none)



### set_upload_path(path)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |

**Returns**: (none)



### set_filename(path, filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| filename | None | - | - |

**Returns**: (none)



### set_max_filesize(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |

**Returns**: (none)



### set_max_size(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |

**Returns**: (none)



### set_max_filename(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |

**Returns**: (none)



### set_max_width(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |

**Returns**: (none)



### set_max_height(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |

**Returns**: (none)



### set_min_width(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |

**Returns**: (none)



### set_min_height(n)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| n | None | - | - |

**Returns**: (none)



### set_allowed_types(types)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| types | None | - | - |

**Returns**: (none)



### set_image_properties(path = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '' | - |

**Returns**: (none)



### set_xss_clean(flag = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| flag | None | FALSE | - |

**Returns**: (none)



### is_image()

**Returns**: (none)



### is_allowed_filetype(ignore_mime = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ignore_mime | None | FALSE | - |

**Returns**: (none)



### is_allowed_filesize()

**Returns**: (none)



### is_allowed_dimensions()

**Returns**: (none)



### validate_upload_path()

**Returns**: (none)



### get_extension(filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |

**Returns**: (none)



### limit_filename_length(filename, length)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |
| length | None | - | - |

**Returns**: (none)



### do_xss_clean()

**Returns**: (none)



### set_error(msg, log_level = 'error')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |
| log_level | None | 'error' | - |

**Returns**: (none)



### display_errors(open = '<p>', close = '</p>')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| open | None | '<p>' | - |
| close | None | '</p>' | - |

**Returns**: (none)



### _prep_filename(filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |

**Returns**: (none)



### _file_mime_type(file)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| file | None | - | - |

**Returns**: (none)


