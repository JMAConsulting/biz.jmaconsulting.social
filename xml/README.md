This method for DAO generation is taken from [Sepa Extension](https://github.com/Project60/org.project60.sepa). Thanks a lot Xavier for the suggestion. 

You need to symlink *[extension_root]/xml/schema/Social* and *[extension_root]/xml/Social.xml* to *[civicrm_root]/xml* folder. This can be done by

```
cd [civicrm_root]/xml/schema
ln -s <extesion_root>/xml/schema/Social Social
ln -s [extension_root]/xml/schema/Social.xml Social.xml
```

You further need to add the following line to *[civicrm_root]/xml/schema/schema.xml*.

```
<xi:include href="Social/files.xml" parse="xml" />
```

Then running *xml/GenCode.php* will generate the DAO file in *[civicrm_root]/CRM/Social/DAO*.
