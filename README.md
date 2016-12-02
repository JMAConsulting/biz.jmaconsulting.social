# CiviSocial - Social Media Integration

This extension to CiviCRM allows users to more easily fill forms and sign petitions using social login. It also allows event registrations in CiviCRM to be reflected in RSVPs for parallel Facebook events. Moreover, it allows CiviCRM admins to integrate multiple social networks and pull any relevant users activity data. Specifically, the extension provides following features:

### Users
Users can avail the following features after logging in to a social network. Currently supported networks are Facebook, Google and Twitter.

 - Public forms (Event registration, petitions, etc.) are auto filled by the data pulled from the associated social newtork.
 - User can choose to reflect event registration in CiviCRM in RSVPs for parallel Facebook event.

### Admin

 - Link Facebook event with CiviCRM event
 - Fill `Add New Event` page form with data from corresponding Facebook event
 - Connect your Facebook Page and Twitter page
 - Post across your Facebook page and Twitter at once
 - See posts and tweets on your Facebook page/twitter account
 - See who has followed you on Twitter

#### Pending updates
The extension is active under developement. Following features can be expected on the official release:

- Pull and store Twitter follower's data into CiviCRM database
- Determine highly active followers based on social activity
- Ability to automatically publish/tweet about events and certain types of activities

=======

## Installation
1. As part of your general CiviCRM installation, set a CiviCRM Extensions Directory at `Administer >> System Settings >> Directories`.
2. Navigate to `Administer >> System Settings >> Manage Extensions`. Beside CiviSocial click Install.

## Configuration
Navigate to `Administer >> CiviSocial >> App Credentials`.

#### Getting Facebook App ID and Secret
1. Go to [Facebook's Developer page](https://developers.facebook.com/apps). Under `My Apps` on top right,  click on **Add a New App**.
2. Click on **Website**.
3. Set display name to your organization's name. Set Contact Email to your email address and set category to **Communication.**.
4. Click **Create App ID**.
5. You will be taken to App Dashboard. Click on **Get Started** button beside **Facebook Login**.
6. Under **Client OAuth Settings**, on **Valid OAuth redirect URIs** enter `[your website URL]/civicrm/civisocial/callback/facebook`.
7. Click on **Save Changes**.
8. Navigate to **Dashboard**. Your App ID and Secret can be retreived from that page.

#### Getting Google Client ID and Secret
1. Go to the [Google API Console](https://console.developers.google.com/project/_/apiui/apis/library).
2. Create a new project by selecting Create a new project.
3. In the sidebar under "API Manager", select Credentials, then select the OAuth consent screen tab.
4. Choose an Email Address, specify a Product Name, and press Save.
5. In the Credentials tab, select the New credentials drop-down list, and choose OAuth client ID.
6. Under Application type, select Web application.
7. In the **Authorized JavaScript origins** field, enter your website URL.
8. In the **Authorized redirect URIs** field, enter `[your website URL]/civicrm/civisocial/callback/googleplus`.
9. Press the Create button.
10. From the resulting OAuth client dialog box, copy the Client ID and Client Secret.

#### Getting Twitter Consumer Key and Secret
1. Go to https://dev.twitter.com/apps/new.
2. Enter your organization's name of **Name** field.
3. Fill description and website.
4. Enter `[your website URL]/civicrm/civisocial/callback/twitter` on **Callback URL** field.
5. Accept the developer agreement and click **Create your Twitter Application**.
6. Go to **Settings** tab and enter `[your website URL]/civicrm/civisocial/privacypolicy` on **Privacy Policy URL** and **Terms of Service URL** fields.
7. Go to **Permissions** tab. Under **Access** select **Read and Write**. 
8. To be able to ask users their email addresses your app needs to be whitelisted by Twitter. To get whitelisted, visit https://support.twitter.com/forms/platform and select `I need access to special permissions` and fill the form (You can get your application ID by going to https://apps.twitter.com/ and clicking you app name. Look for a number on your address bar - https://apps.twitter.com/app/[your app ID]) and submit. After you are whitelisted go to **Settings** tabl. Under **Additional Permissions** check *Request email addresses from users*.
8. Go to **Keys and Access Tokens** tab and copy Consumer Key and Consumer Secret.
  
After you have configured one or more app credentials, click Save.

### Enabling Facebook Event Integration
To be able to link Facebook events to your CiviCRM event you must enable *Facebook Event Integration*.

After you have configured credentials for Facebook (`Administer >> CiviSocial >> App Credentials`), navigate to `Adminster >> CiviSocial >> Social Networks`. Click on *Integrate Facebook Events*.

### Enabling Social Dashboard
To be able to access social dashboard, you must connect the correpsonding or all social networks.

After you have configured credentials for Facebook and/or Twitter, navigate to `Adminster >> CiviSocial >> Social Networks`. Click on **Connect Facebook Page** to connect your Facebook page to CiviCRM. Likewise, click on **Connect Twitter** to connect your Twitter account.

You will now be able to access social dashboard(s) from **Social** menu.
