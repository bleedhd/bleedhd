# Getunik SASS/LESS Mixin Library

## How to use this in a project
Usually you want to add this repo as a GIT submodule to your project. From there you have to include the LESS or SASS Version via import directive in your main style file. Examples:

```
// demo include for LESS version:
@import (less) "your_submodule_dir/_getunik_mixins.less";

```

```
// demo include for SASS version:
@import "your_submodule_dir/_getunik_mixins.scss";

```

## Mixins Documentation
Checkout the annotation comments inside the files.

## Demo Folder
Inside the demo/ folder there is a html test file with some form elements that will be replaced with svg images. In this folder there is a Grunt task as well to compile the demo. To use it you first have to install the required dependencies:

```
$npm install
```

After this you can run grunt with:

```
$grunt
```
The default tasks watches the LESS and SASS files for the mixins and the wrapper files in the demo folder as well. As soon as you change something in the files, the corresponding version (LESS or SASS) will be compiled to styles.css - which is included on the demo/index.html file -> nice for debugging/testing.



