### Usages

#### Build index

`php bin/console binary-tree:build -in documents.json -f name`

Options:

- `in` - the source file name (should be strored in folder data);
- `f` - the field to compare;

#### Search

`php bin/console binary-tree:search -in documents.json -f name -s test -i 1`
 
Options:

 - `in` - the source file name (should be strored in folder data);
 - `f` - the field to compare;
 - `s` - the search string;
 - `i` - 0 - search without index, 1 - search with index.
