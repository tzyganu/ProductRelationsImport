ProductRelationsImport
======================

Magento Product relations import tool
------

This Magento extension allows you to import via manual input or file import the product relations:
 - Related products
 - Up-sells  
 - Cross-sells.

How to use:
---------
After installation you should see a new menu item under the `Catalog` main menu called `Import product relations`.
If that does not appear, clear the cache. If it's still no there then something is very wrong.
After clicking on the menu item you should see a form with the following fields.

**Import type**  
 - *Direct input* - allows you to input the data manually  
 - *File Upload* - allows you to upload a csv with the values to import  

**Relation type** - from here you can select what type of relations you want to import.  
 - *Related products*  
 - *Up-sells*  
 - *Cross-sells*  

**Action** - select the action you want to take on import  
 - *Replace existing relations* - it will delete the current relations for the products found in the import data and add only the ones specified  
 - *Merge existing relations* - it will add the import data over the existing relations  

**Work with** - specify if the values in the import data are product ids or product SKUs  

**Import rules** - specify how the import data should be parsed.  
 - *Relate all on one line to first product in line.* - the import data will be parsed one line at a time and the first product in the line will be considered as the main product. The rest of the products in the line will be added as relations to the main product.  
 - *Relate all products on one line* - the import data will be parsed one line at a time and all products on one line will be related among themselves. If a line contains 'A,B,C' the B & C will be related to A, A & C will be related to B and A & B will be related to C.  
 - *Relate all products among themselves* - works the same as *Relate all products on one line* but all the products found in the import data will be related among themselves regardless of the line they are in.  

**Related products identifiers** - fill in your data to import. Use comma (,) to separate the product identifiers and semicolon (:) to separate position from the product identifier.  
Example:
Let's say you are working with product ids and you want to import up sells. and for import rules you selected *Relate all products on one line*.

<pre><code>
34,55:2,17:4
99:1,80:10
</code></pre>

The values above mean:  
 - The product with id `34` will have 2 upsells: product with id `55` on position `2` and product with id `17` on position `4`.  
 - The product with id `55` will have 2 upsells: product with id `34` on position `0` (because the position was not specified) and product with id `17` on position `4`.  
 - The product with id `17` will have 2 upsells: product with id `34` on position `0` (because the position was not specified) and product with id `55` on position `2`.  
 - The product with id `99` will have one upsell: product with id `80` on position `10`  
 - The product with id `80` will have one upsell: product with id `99` on position `1`  

**File to import** - is the same thing as the **Related products identifiers** the only difference is that here you can upload a csv file. Keep the same format as described in the section above.  

Uninstall:
---------
Remove the following files and folders:  

 - app/code/community/Easylife/Relations/  
 - app/design/adminhtml/default/default/template/easylife_relations/tooltip.phtml  
 - app/etc/modules/Easylife_Relations.xml  
 - app/locale/en\_US/Easylife\_Relations.csv  

Conflicts:
--------
The extension does not rewrite any core class so it shouldn't conflict with other extensions.

Bug report:
--------
<a href="https://github.com/tzyganu/ProductRelationsImport/issues">Submit any bugs or feature requests here</a>
