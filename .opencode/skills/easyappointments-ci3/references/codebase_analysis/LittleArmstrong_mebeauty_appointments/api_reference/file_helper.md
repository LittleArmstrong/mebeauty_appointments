# API Reference: file_helper.php

**Language**: PHP

**Source**: `system/helpers/file_helper.php`

---

## Functions

### write_file(path, data, mode = 'wb')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| data | None | - | - |
| mode | None | 'wb' | - |

**Returns**: (none)



### delete_files(path, del_dir = FALSE, htdocs = FALSE, _level = 0)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| del_dir | None | FALSE | - |
| htdocs | None | FALSE | - |
| _level | None | 0 | - |

**Returns**: (none)



### get_filenames(source_dir, include_path = FALSE, _recursion = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| source_dir | None | - | - |
| include_path | None | FALSE | - |
| _recursion | None | FALSE | - |

**Returns**: (none)



### get_dir_file_info(source_dir, top_level_only = TRUE, _recursion = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| source_dir | None | - | - |
| top_level_only | None | TRUE | - |
| _recursion | None | FALSE | - |

**Returns**: (none)



### get_file_info(file, returned_values = array('name', 'server_path', 'size', 'date')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| file | None | - | - |
| returned_values | None | array('name' | - |
| 'server_path' | None | - | - |
| 'size' | None | - | - |
| 'date' | None | - | - |

**Returns**: (none)



### get_mime_by_extension(filename)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| filename | None | - | - |

**Returns**: (none)



### symbolic_permissions(perms)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| perms | None | - | - |

**Returns**: (none)



### octal_permissions(perms)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| perms | None | - | - |

**Returns**: (none)


