# API Reference: Ftp.php

**Language**: PHP

**Source**: `system/libraries/Ftp.php`

---

## Classes

### CI_FTP

**Inherits from**: (none)

#### Methods

##### __construct(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |


##### initialize(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |


##### connect(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |


##### _login()


##### _is_conn()


##### changedir(path, suppress_debug = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| suppress_debug | None | FALSE | - |


##### mkdir(path, permissions = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| permissions | None | NULL | - |


##### upload(locpath, rempath, mode = 'auto', permissions = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| locpath | None | - | - |
| rempath | None | - | - |
| mode | None | 'auto' | - |
| permissions | None | NULL | - |


##### download(rempath, locpath, mode = 'auto')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| rempath | None | - | - |
| locpath | None | - | - |
| mode | None | 'auto' | - |


##### rename(old_file, new_file, move = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| old_file | None | - | - |
| new_file | None | - | - |
| move | None | FALSE | - |


##### move(old_file, new_file)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| old_file | None | - | - |
| new_file | None | - | - |


##### delete_file(filepath)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filepath | None | - | - |


##### delete_dir(filepath)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filepath | None | - | - |


##### chmod(path, perm)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| perm | None | - | - |


##### list_files(path = '.')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '.' | - |


##### mirror(locpath, rempath)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| locpath | None | - | - |
| rempath | None | - | - |


##### _getext(filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |


##### _settype(ext)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ext | None | - | - |


##### close()


##### _error(line)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| line | None | - | - |




## Functions

### __construct(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |

**Returns**: (none)



### initialize(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |

**Returns**: (none)



### connect(config = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| config | None | array( | - |

**Returns**: (none)



### _login()

**Returns**: (none)



### _is_conn()

**Returns**: (none)



### changedir(path, suppress_debug = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| suppress_debug | None | FALSE | - |

**Returns**: (none)



### mkdir(path, permissions = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| permissions | None | NULL | - |

**Returns**: (none)



### upload(locpath, rempath, mode = 'auto', permissions = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| locpath | None | - | - |
| rempath | None | - | - |
| mode | None | 'auto' | - |
| permissions | None | NULL | - |

**Returns**: (none)



### download(rempath, locpath, mode = 'auto')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| rempath | None | - | - |
| locpath | None | - | - |
| mode | None | 'auto' | - |

**Returns**: (none)



### rename(old_file, new_file, move = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| old_file | None | - | - |
| new_file | None | - | - |
| move | None | FALSE | - |

**Returns**: (none)



### move(old_file, new_file)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| old_file | None | - | - |
| new_file | None | - | - |

**Returns**: (none)



### delete_file(filepath)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filepath | None | - | - |

**Returns**: (none)



### delete_dir(filepath)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filepath | None | - | - |

**Returns**: (none)



### chmod(path, perm)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| perm | None | - | - |

**Returns**: (none)



### list_files(path = '.')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '.' | - |

**Returns**: (none)



### mirror(locpath, rempath)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| locpath | None | - | - |
| rempath | None | - | - |

**Returns**: (none)



### _getext(filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |

**Returns**: (none)



### _settype(ext)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| ext | None | - | - |

**Returns**: (none)



### close()

**Returns**: (none)



### _error(line)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| line | None | - | - |

**Returns**: (none)


