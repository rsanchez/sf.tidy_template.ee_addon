# Tidy Template with HTML Tidy in Expressionengine 2.4+

Finally we got a template_post_parse hook in EE, and you know what that means? Time to get rid of all that awful whitespace and the wonky tabbing.

# 1, 2, 3 PARTY.

1. Tweak the Tidy config.
2. Put it in your third_party folder.
3. Enable
4. (optional) Configure your [options](http://tidy.sourceforge.net/docs/quickref.html) in the extension settings
5. (optional) Use the {tidy:options} tag:

	`{tidy:options indent="yes" wrap="200"}`

# Time spent on this

The options aren't tweaked, and I spent like 5 minutes on this. Please tweak Tidy config, stop it from adding the whole HTML markup if you only got a poor <b> or improve it any way you want.