# API Reference: Loader.php

**Language**: PHP

**Source**: `system/core/Loader.php`

---

## Classes

### CI_Loader

**Inherits from**: (none)

#### Methods

##### __get(name: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |

**Returns**: `mixed`


##### __set(name: string, value: mixed) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |
| value | mixed | - | - |

**Returns**: `void`


##### __construct()


##### initialize()


##### _get_validation_object()


##### is_loaded(class)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| class | None | - | - |


##### library(library, params = NULL, object_name = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| library | None | - | - |
| params | None | NULL | - |
| object_name | None | NULL | - |


##### model(model, name = '', db_conn = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| model | None | - | - |
| name | None | '' | - |
| db_conn | None | FALSE | - |


##### database(params = '', return = FALSE, query_builder = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | '' | - |
| return | None | FALSE | - |
| query_builder | None | NULL | - |


##### dbutil(db = NULL, return = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| db | None | NULL | - |
| return | None | FALSE | - |


##### dbforge(db = NULL, return = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| db | None | NULL | - |
| return | None | FALSE | - |


##### view(view, vars = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| view | None | - | - |
| vars | None | array( | - |


##### file(path, return = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| return | None | FALSE | - |


##### vars(vars, val = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vars | None | - | - |
| val | None | '' | - |


##### clear_vars()


##### get_var(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |


##### get_vars()


##### helper(helpers = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| helpers | None | array( | - |


##### helpers(helpers = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| helpers | None | array( | - |


##### language(files, lang = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| files | None | - | - |
| lang | None | '' | - |


##### config(file, use_sections = FALSE, fail_gracefully = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| file | None | - | - |
| use_sections | None | FALSE | - |
| fail_gracefully | None | FALSE | - |


##### driver(library, params = NULL, object_name = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| library | None | - | - |
| params | None | NULL | - |
| object_name | None | NULL | - |


##### add_package_path(path, view_cascade = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| view_cascade | None | TRUE | - |


##### get_package_paths(include_base = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| include_base | None | FALSE | - |


##### remove_package_path(path = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '' | - |


##### _ci_load(_ci_data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| _ci_data | None | - | - |


##### _ci_load_library(class, params = NULL, object_name = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| class | None | - | - |
| params | None | NULL | - |
| object_name | None | NULL | - |


##### _ci_load_stock_library(library_name, file_path, params, object_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| library_name | None | - | - |
| file_path | None | - | - |
| params | None | - | - |
| object_name | None | - | - |


##### _ci_init_library(class, prefix, config = FALSE, object_name = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| class | None | - | - |
| prefix | None | - | - |
| config | None | FALSE | - |
| object_name | None | NULL | - |


##### _ci_autoloader()


##### _ci_prepare_view_vars(vars)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vars | None | - | - |




## Functions

### __get(name: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |

**Returns**: `mixed`



### __set(name: string, value: mixed) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | string | - | - |
| value | mixed | - | - |

**Returns**: `void`



### __construct()

**Returns**: (none)



### initialize()

**Returns**: (none)



### _get_validation_object()

**Returns**: (none)



### is_loaded(class)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| class | None | - | - |

**Returns**: (none)



### library(library, params = NULL, object_name = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| library | None | - | - |
| params | None | NULL | - |
| object_name | None | NULL | - |

**Returns**: (none)



### model(model, name = '', db_conn = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| model | None | - | - |
| name | None | '' | - |
| db_conn | None | FALSE | - |

**Returns**: (none)



### database(params = '', return = FALSE, query_builder = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| params | None | '' | - |
| return | None | FALSE | - |
| query_builder | None | NULL | - |

**Returns**: (none)



### dbutil(db = NULL, return = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| db | None | NULL | - |
| return | None | FALSE | - |

**Returns**: (none)



### dbforge(db = NULL, return = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| db | None | NULL | - |
| return | None | FALSE | - |

**Returns**: (none)



### view(view, vars = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| view | None | - | - |
| vars | None | array( | - |

**Returns**: (none)



### file(path, return = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| return | None | FALSE | - |

**Returns**: (none)



### vars(vars, val = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vars | None | - | - |
| val | None | '' | - |

**Returns**: (none)



### clear_vars()

**Returns**: (none)



### get_var(key)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| key | None | - | - |

**Returns**: (none)



### get_vars()

**Returns**: (none)



### helper(helpers = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| helpers | None | array( | - |

**Returns**: (none)



### helpers(helpers = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| helpers | None | array( | - |

**Returns**: (none)



### language(files, lang = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| files | None | - | - |
| lang | None | '' | - |

**Returns**: (none)



### config(file, use_sections = FALSE, fail_gracefully = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| file | None | - | - |
| use_sections | None | FALSE | - |
| fail_gracefully | None | FALSE | - |

**Returns**: (none)



### driver(library, params = NULL, object_name = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| library | None | - | - |
| params | None | NULL | - |
| object_name | None | NULL | - |

**Returns**: (none)



### add_package_path(path, view_cascade = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | - | - |
| view_cascade | None | TRUE | - |

**Returns**: (none)



### get_package_paths(include_base = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| include_base | None | FALSE | - |

**Returns**: (none)



### remove_package_path(path = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| path | None | '' | - |

**Returns**: (none)



### _ci_load(_ci_data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| _ci_data | None | - | - |

**Returns**: (none)



### _ci_load_library(class, params = NULL, object_name = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| class | None | - | - |
| params | None | NULL | - |
| object_name | None | NULL | - |

**Returns**: (none)



### _ci_load_stock_library(library_name, file_path, params, object_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| library_name | None | - | - |
| file_path | None | - | - |
| params | None | - | - |
| object_name | None | - | - |

**Returns**: (none)



### _ci_init_library(class, prefix, config = FALSE, object_name = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| class | None | - | - |
| prefix | None | - | - |
| config | None | FALSE | - |
| object_name | None | NULL | - |

**Returns**: (none)



### _ci_autoloader()

**Returns**: (none)



### _ci_prepare_view_vars(vars)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| vars | None | - | - |

**Returns**: (none)


