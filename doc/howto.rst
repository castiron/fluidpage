================================
FluidPage:
================================

------------------------------------------------------------
A Brief Introduction to Creating a TYPO3 site with FluidPage
------------------------------------------------------------

About FluidPage
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This extension was developed by the good people at Cast Iron Coding. It was created out of the impression that the two common solutions to templating in TYPO3, Templavoila and AutoMakeTemplate, were both insufficient. Templavoila tends to not store data in a normalized format because of its heavy reliance on XML. Indeed, it tends to obfuscate the relationship between pages and page content, which can cause problems on many kinds of TYPO3 projects. Moreover, the UI of Templavoila has often been somewhat out of sync with UI of TYPO3 in general, which we believe causes problems for users. The Typoscript-driven approach to templating, which is simplified somewhat by AutoMakeTemplate, is time consuming. Whenever a new element is added to the page, TypoScript has to be written so that objects are mapped to the correct spot in the template. We find this approach to be rather tedious. Moreover, the long-standing problem with classic TYPO3 templating has been the limitation of the 4 columns in the page module: left, normal, right and border.

Recent versions of TYPO3 ship with Fluid and backend-layouts. Fluid, as you no doubt know if you're reading this, is a powerful templating engine. There is no reason why it should only be usable in TYPO3 extensions, and we believe it is time to fully embrace it in the TYPO3 core and use it as _the_ core templating mechanism. Backend layouts solve the important problem of the UI in the page module by allowing admins the flexibility to create complex layouts and a corresponding editing UI. In the near future, we hope to see the gridelements extension in the TYPO3 core, which will address the need for nested content elements (currently only really met by TemplaVoila) in the page module.

This extension is still rather alpha. We are using it in multiple production sites and are improving the extension on a daily basis. This extension is, thankfully, a simple extension. Instead of trying to provide complex functionality, it tries to provide a sensible way to tie together existing aspects of TYPO3 (backend layouts, fluid templates, and typoscript). If you take a look at the pi1 script that ships with this extension, you will see that it is quite simple, and we hope it remains that way. With simplicity comes stability. Simple as it may be, we believe this extension opens up a lot of doors in TYPO3 and brings real power to the TYPO3 site implementation process.

FluidPage Development
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

We are currently updating this extension on Github. I'll be honest. Here at CIC, Git is so wound up in our process it's difficult for us to think about hosting this in SVN on forge.typo3.org. If people start using this extension and demand picks up, we may re-evaluate that decision. Once we feel the extension is stable, we will release a version to the TER. We're highly committed to this approach to building sites with TYPO3; if you find an issue, we will do our very best to fix it promptly. Please report issues on the github page at https://github.com/castiron/fluidpage/issues.

1. Install the fluidpage extension
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The simplest way to do this, for now, is to clone the repository using git, although you can also install the extension from the TER. To clone it from github, execute this command in the typo3conf/ext directory::
	
	git clone git@github.com:castiron/fluidpage.git fluidpage

Once that's done, you can install the extension in the extension manager as with any extension. The FluidPage extension does not add any database fields to TYPO3. However, it does make some minor changes to the TCA by way of TSconfig. Out of the box, fluid page will change the label on the "backend layout" field on page records to "Page Template (this page only)". The child page backend layout field will be changed to "Page Template (subpages of this page)". It makes this UI change in order to make it more clear to content editors, who may not understand the concept of a backend layout, that these layout fields are used to select the template that will be used in both the backend and the frontend.

2. Setup the page tree
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

To work through this tutorial, you'll need a simple page tree as a starting point. Create a new root page and beneath it three or four additional pages. Create a folder to store your backend layouts.

3. Create a back-end layout
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

In the storage folder you created in the previous step, make a back-end layout record. For the purposes of this how-to, go ahead and create a backend layout record with two columns called left and right. Assign a column number of "1" to the left column and a column number of "2" to the right column. Your layout configuration should look more or less like this when you're done::

	backend_layout {
		colCount = 2
		rowCount = 1
		rows {
			1 {
				columns {
					1 {
						name = Left Column
						colPos = 1
					}
					2 {
						name = Right Column
						colPos = 2
					}
				}
			}
		}
	}

Set the title of the backend layout to "two column" and save the record.

4. Assign your back-end layout to pages in the page tree
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Edit the root page in the page tree. You'll see two fields on the "appearance" tab for selecting the page template. Set both of them to your newly created back-end layout.

5. Create a Typoscript template
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

On your root page (or wherever you like to put your master Typoscript template) create a new template. Edit the template and switch to the "include" tab. Include CSS Styled Content (of course) and the "Fluid Page Static Typoscript Template" options. Edit the setup field of the template and add the following::

	lib.primaryNav = HMENU
	lib.primaryNav {
		entryLevel = 0
		wrap = <ul id="topNav" class="nav">|</ul>
		1 = TMENU
		1.expAll = 1
		1.noBlur = 1
		1.NO {
			stdWrap.htmlSpecialChars = 1
			wrapItemAndSub = <li class="first">|</li>|*|<li>|</li>|*|<li class="last">|</li>
		}
		1.ACT = 1
		1.ACT < .1.NO
		1.ACT {
			ATagParams = class="active"
		}
	}

	lib.secondaryNav < lib.primaryNav
	lib.secondaryNav {
		wrap >
		1.expAll = 0
		1.wrap = <ul class="nav">|</ul>
		2.expAll = 0
	}

	lib.content.leftCol < styles.content.get
	lib.content.leftCol.select.where = colPos=1

	lib.content.rightCol < styles.content.get
	lib.content.rightCol.select.where = colPos=2
	lib.content.rightCol.slide = -1

	page = PAGE
	page {
		10 < plugin.tx_fluidpage_pi1
		10 {
			settings {
				partialRootPath = EXT:fluidpage/Resources/Public/Partials/
		        variables {
		        	tagline  = TEXT
		        	tagline {
						value = This tagline on page "{field:title}" is an example of a global template variable
						insertData = 1
						stdWrap = <strong>|</strong>
		        	}
		        }
			}
			templates {
				1 {
					file = EXT:fluidpage/Resources/Public/html/main.html
					constants {
						showLeftColumn = 1
						showRightColumn = 1
						bodyClass = column-2
					}
					variables {
						tagline  = TEXT
		        		tagline {
							value = Example: template variable overriding a global variables on page "{field:title}."
							insertData = 1
							stdWrap = <strong>|</strong>
		        		}
					}
				}
				2 {
					file = EXT:fluidpage/Resources/Public/html/main.html
					constants {
						showLeftColumn = 1
						showRightColumn = 0
						bodyClass = column-1
					}
				}
			}
		}
		
		typeNum = 0
		
		# Unset the body tag, as it's is provided by the fluid template
		bodyTagCObject = TEXT
		bodyTagCObject.noTrimWrap = | | |
		
		includeCSS {
			10 = typo3conf/ext/fluidpage/Resources/Public/css/style.css
		}
	}
	
It's worth pausing for a moment here to review what's happening in this typoscript. The first two objects are lib.primaryNav and lib.secondaryNav. These are simple HMENU objects that are used to render the primary and secondary navigation. I've included them here so that you can see how you can reference a typoscript object in a fluidPage template using a fluid view helper (eg: <f:CObject typoscriptObjectPath="lib.secondaryNav" />). The ability to reference Typoscript CObjects directly in your fluidPage template is an important part of this approach because it means there is no longer a mapping step, as there was in TemplaVoila and in AutoMakeTemplate. Instead of mapping Typoscript objects to templates, you simply reference them directly in your template by way of view helpers.

After the menu objects, there are some lib.content declarations. In this section, we're simple declaring the various content areas that are available in the backend. Remember when you set the left column to have a colPos of 1? This is where we reference that column position and assign the rendered content for that column to a typoscript object, which can in turn be rendered in the fluid template just like the primary or secondary navigation. Notice that the right column is configured to slide down the page tree, using existing Typoscript properties.

In the page declaration we tell TYPO3 to take the output of plugin.tx_fluidpage_pi1 and assign it to page.10. This is similar to how TemplaVoila works. The "templates" section of the fluidPage configuration is the key part. Each item under templates (1, 2, etc) refers to the UID of a back-end layout. Let's look at one template declaration::

	templates {
		1 {
			file = EXT:fluidpage/Resources/Public/html/main.html
			constants {
				showLeftColumn = 1
				showRightColumn = 1
			}
		}
	}

This tells FluidPage that when the backend layout for a given page is the backend layout record with the UID of 1, it should use the fluid template file at EXT:fluidpage/Resources/html/main.html. The path to this file could, of course, be a file in fileadmin/templates. At Cast Iron Coding we prefer to store all template assets in an extension, so we've made sure that FluidPage can parse paths beginning with EXT.

The constants section contains arbitrary constants declarations which can be referenced in the fluid template as values in the {constants} array. For example, a fluid template could contain a condition that referenced a constant::

	<f:if condition="{constants.showLeftColumn} == 1">
	<div id="main">
		<f:CObject typoscriptObjectPath="lib.breadcrumb" />			
		<div id="mainContent">
			<h2>Left Column</h2>
			<f:CObject typoscriptObjectPath="lib.content.leftCol" />
		</div>
	</div>

Constants make it possible to have, for example, a single template with sections that show up depending on how a specific template is configured. Constants also make it easier to re-use template functionality in multiple backend layouts.

To see how the actual fluid template works, take a look at the file at EXT:fluidpage/Resources/Public/html/main.html. Notice that the page record is available to the fluid view as {page}. This makes it possible to include the page title, for example, in the header by simply adding {page.title} to the fluid template. For light-weight rendering tasks like this, there's no need to create a whole typoscript object.

