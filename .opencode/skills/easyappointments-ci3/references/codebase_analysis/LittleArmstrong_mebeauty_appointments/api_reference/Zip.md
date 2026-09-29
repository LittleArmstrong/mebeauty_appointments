# API Reference: Zip.php

**Language**: PHP

**Source**: `system/libraries/Zip.php`

---

## Classes

### CI_Zip

**Inherits from**: (none)

#### Methods

##### __construct()


##### add_dir(directory)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| directory | None | - | - |


##### _get_mod_time(dir)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| dir | None | - | - |


##### _add_dir(dir, file_mtime, file_mdate)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| dir | None | - | - |
| file_mtime | None | - | - |
| file_mdate | None | - | - |


##### add_data(filepath, data = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filepath | None | - | - |
| data | None | NULL | - |


##### _add_data(filepath, data, file_mtime, file_mdate)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filepath | None | - | - |
| data | None | - | - |
| file_mtime | None | - | - |
| file_mdate | None | - | - |


##### read_file(path, archive_filepath = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| archive_filepath | None | FALSE | - |


##### read_dir(path, preserve_filepath = TRUE, root_path = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| preserve_filepath | None | TRUE | - |
| root_path | None | NULL | - |


##### get_zip()


##### archive(filepath)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filepath | None | - | - |


##### download(filename = 'backup.zip')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | 'backup.zip' | - |


##### clear_data()


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

### __construct()

**Returns**: (none)



### add_dir(directory)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| directory | None | - | - |

**Returns**: (none)



### _get_mod_time(dir)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| dir | None | - | - |

**Returns**: (none)



### _add_dir(dir, file_mtime, file_mdate)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| dir | None | - | - |
| file_mtime | None | - | - |
| file_mdate | None | - | - |

**Returns**: (none)



### add_data(filepath, data = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filepath | None | - | - |
| data | None | NULL | - |

**Returns**: (none)



### _add_data(filepath, data, file_mtime, file_mdate)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filepath | None | - | - |
| data | None | - | - |
| file_mtime | None | - | - |
| file_mdate | None | - | - |

**Returns**: (none)



### read_file(path, archive_filepath = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| archive_filepath | None | FALSE | - |

**Returns**: (none)



### read_dir(path, preserve_filepath = TRUE, root_path = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| preserve_filepath | None | TRUE | - |
| root_path | None | NULL | - |

**Returns**: (none)



### get_zip()

**Returns**: (none)



### archive(filepath)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filepath | None | - | - |

**Returns**: (none)



### download(filename = 'backup.zip')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | 'backup.zip' | - |

**Returns**: (none)



### clear_data()

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


