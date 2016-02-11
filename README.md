# DEPRECATED, USE: https://github.com/sopinet/UploadFilesBundle

upload-magic-bundle
===================

Symfony2 Bundle - Easy integration for DropzoneJS and OneupUploaderBundle 

## Prerequisites

This bundle is tested using Symfony2 versions 2.3+.

## Installation:

### Step 1: Add Bundle

1. Add to composer: 

```json
"sopinet/upload-magic-bundle": "dev-master"
```

2. Add to AppKernel: 

```php
new Oneup\UploaderBundle\OneupUploaderBundle(),
new Sopinet\Bundle\UploadMagicBundle\SopinetUploadMagicBundle(),
```

3. Add routing for delete

```
upload:
    resource: "@SopinetUploadMagicBundle/Controller/UploadController.php"
    type:     annotation
```

### Step 2: Configure OneupUploaderBundle

1. Add configuration (config.yml) any like:

```yaml
oneup_uploader:
    mappings:
        gallery:
            frontend: dropzone
```

2. Configure routing

```yaml
oneup_uploader:
    resource: .
    type: uploader
```

2. More options from OneupUploaderBundle,https://github.com/1up-lab/OneupUploaderBundle/blob/master/Resources/doc/index.md but upload-magic-bundle only support dropzone frontend method.

### Step 3: Define your Entity

1. You can use UploadMagic trait, so:

```php
namespace yourBundle\BaseBundle\Entity;

use ...

/**
 * @ORM\Entity
 * @ORM\Table(name="file")
 * @DoctrineAssert\UniqueEntity("id")
 */
class File
{
	
	use \Sopinet\Bundle\UploadMagicBundle\Model\UploadMagic;
	...
}
```

2. You can add relation and another attributes to your entity:

```php
  ...
	/**
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="files")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	protected $category;
	...
```

### Step 4: Add your form

1. Add CSS

```twig
{% include 'SopinetUploadMagicBundle:Upload:simple_css.html.twig' %}
```

2. Add form

```twig
								{% include 'SopinetUploadMagicBundle:Upload:simple.html.twig'
								  	with {
								  		'name': 'demo',
								  		'type': 'gallery',
								  		'id': 0, 
								  		'entity': 'myBundle_BaseBundle_File',
								  		'param1': 'title',
                			'value1': 'A title for...',
                			'param2': 'myBundle_BaseBundle_Category',
                			'value2': guild.id,
                			'icon': 'fa fa-plus-circle'
										}
								  %}
```

In param1 i use "title", and Listener will do "setTitle".

With param2 it use an entity, and it will do findbyoneid and "set" again.

More documentation about params form is coming... ;)

3. Add js, one time

```
{% include 'SopinetUploadMagicBundle:Upload:simple_js.html.twig' %}
```

If you have errors about load time, you can use:
```
{% include 'SopinetUploadMagicBundle:Upload:simple_js.timeout.html.twig' %}
```

4. Add blockjs, for each form

```
	{% include 'SopinetUploadMagicBundle:Upload:simple_blockjs.html.twig' 
		with {
		  'name': 'demo', 
		  'type': 'gallery', 
		  'files': files_already_saved_array, 
		  'limit': 1, 
		  'entity': 'myBundle_BaseBundle_File'
		  # Optional
		  'preview': true
		} 
	%}
```

## More JS, trigger

What file is uploading or file is removed, you can capture this event in JQuery and do anything.

### Events

```js
$( "body" ).on( "removedFile", function( event, data) {
  // data, "ok" or "ko"
});

$("body").on("uploadedFile", function(event, data) {
  // data, "ok"
});
```

### Sample

```js
$( "body" ).on( "removedFile", function( event, data) {
	if (data == "ok") {
		$.gritter.add({
			title: '<i class="fa fa-check-circle"></i> Acción realizada con éxito',
			text: 'Has eliminado un fichero',
			sticky: false,
			time: '',
			class_name: 'gritter-success'
		});
	} else {
		$.gritter.add({
			title: '<i class="fa fa-times-circle"></i> Ha ocurrido un error',
			text: 'No se ha podido eliminar el fichero',
			sticky: false,
			time: '',
			class_name: 'gritter-danger'
		});								
	}			
});
$("body").on("uploadedFile", function(event, data) {
	$.gritter.add({
		title: '<i class="fa fa-check-circle"></i> Acción realizada con éxito',
		text: 'Has guardado un fichero',
		sticky: false,
		time: '',
		class_name: 'gritter-success'
	});			
});
```

TODO
====

1. Better documentation about params to Form
2. More is coming...
