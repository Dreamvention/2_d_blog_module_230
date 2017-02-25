#d_blog_module

[DEMO](https://dreamvention.myshopunity.com/230/d_blog_module/index.php?route=d_blog_module/category)

The blog framework for Opencart 2.x. It is Open sourse and avalible for anyone to include into thier theme of project. 

It has the most important features for a blog:
1. Posts
2. Categories
3. Reviews
4. Authors
5. Author groups

You can also extend the functionality by adding
1. d_blog_module_modules - includes over 10 modules like featured posts, latest posts and more
2. d_seo_module_blog - this implements SEO features into the blog and requires d_seo_module (free) to be installed,
3. d_social_login - lite or pro. Adds Social login functionality to reviews.
4. d_visual_designer - lite or pro. Adds support for the best Opencart live Page Builder.

##Installation
1. Shopunity Required. You must first install [Shopunity](https://shopunity.net).
2. Donwload the Archive
3. Unzip 
4. Uplaod all files into ROOT folder of your opencart installation
5. Go to Admin -> Extensions -> Modules. 
6. Click Install Blog module Manager (shopunity must be installed and activated)
7. You should also have a new module visible - Twig Manager (if not - you must install [Shopunity](https://shopunity.net) module and redo the process)
8. If you are running an opencart store below 2.3.0.0 - you will need to activate the Event Compatibility option in Event Manager -> Settings.
9. Then Go to Twig Manager -> Tab Settings and activate Twig support.
10. Then go to Blog Module and switch the status ON.

###Requirements
1. d_blog_module requires TWIG templating engine to be installed and supported by Opencart. You can add Twig support with the [d_twig_manager](https://github.com/Dreamvention/2_d_twig_manager).
2. It also requires events to be avalible. Opencart 2.3.0.x and above have support for events, yet if you want to use the blog below 2.3.x - you will need to install the [d_event_manager](https://github.com/Dreamvention/2_d_event_manager) (d_twig_manager also requires it). 

##Support
Please create a Issue in this git repository and we will get back to you.