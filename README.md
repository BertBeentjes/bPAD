bPAD
====

bPAD stands for Bert's Play And Design cms. It is a flexible, open source (web) cms that started life in 2010. The need came from my efforts to create device independent designs for my websites. That most webcms's are page-oriented, makes it more difficult to create great websites.

So I decided to create my own cms based on two assumptions:

- content is created in blocks. A page can contain multiple blocks. Blocks can contain multiple blocks.
- all interactions of the (web-)frontend with the backend use a call and response system (api).

The goal: to give the editor of the site full control over the structure and content of the site, while maintaining cross-device compatibility. Even creating the option to use the content in a non-web frontend (app or whatever).

The scope of bPAD so far:

- easy content creation
- templates for content blocks
- responsive and adaptive design capabilities
- content item level authorization model
- full versioning

The technology used:

- PHP
- MySQL

Backend libraries:

- MobileDetect
- SimpleImage

For the web frontend:

- jQuery 
- Bootstrap


Status and developments:

The first version of bPAD was created in 2010-2013. But as with most projects started from scratch, the code base was created with a slightly different focus than the final product. Creating some issues with the maintaince of the code and some quality issues. So when in 2013 I decided to go open source, a full code rebuild was necessary to get the code up to standards.

This code rebuild is currently underway and progressing according to plan. The current version here on GitHub is basically usable, but not finished. The aim is to have a stable alpha version in Q2 of 2014. This version will be usable for stand alone websites.

After this initial version, the aim is to create a beta version of the site that also supports content, content block and design sharing between sites. This makes it possible for users to share content blocks or the design of content blocks with other sites, without any installation. This beta release is planned for the end of 2014 or the beginning of 2015.


Installation:

The code base provided here has to be used in combination with a database. This database is not yet fully available. The way bPAD is set up, an empty database is not enough, it needs to be filled with content block templates. This part is under development and will be available along with the full alpha version. Before that, it can be requested by sending me an email.

The development of the database coincides with the creation of a website with basic information and examples of the use of bPAD.
