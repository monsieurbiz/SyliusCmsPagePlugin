# Upgrade from dev to 1.X.X

In the 1.x we changed the table name of entities.
If you have a running instance of the plugin you can run this SQL to rename the tables : 

```sql
RENAME TABLE `mbiz_cms_page` TO `monsieurbiz_cms_page`;
RENAME TABLE `mbiz_cms_page_translation` TO `monsieurbiz_cms_page_translation`;
RENAME TABLE `mbiz_cms_page_channels` TO `monsieurbiz_cms_page_channels`;
```
     
We upgraded also the [Rich Editor to the 2.0 version]((https://github.com/monsieurbiz/SyliusRichEditorPlugin/blob/master/UPGRADE-2.0.md)).  
