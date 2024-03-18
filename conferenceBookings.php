<?php

# Class to create a conference bookings system
class conferenceBookings extends frontControllerApplication
{
	# Function to assign defaults additional to the general application defaults
	public function defaults ()
	{
		# Specify available arguments as defaults or as NULL (to represent a required argument)
		$defaults = array (
			'applicationName' => 'Conference bookings',
			'div' => strtolower (__CLASS__),
			'database' => 'conferencebookings',
			'table' => 'conference',
			'databaseStrictWhere' => true,
			'administrators' => true,
			'useEditing' => true,
			'useSettings' => true,
			'internalAuth' => true,
			'authentication' => true,	// All pages require login
			'settingsTableExplodeTextarea' => array ('sessions', 'projects'),
			'useCamUniLookup' => false,
		);
		
		# Return the defaults
		return $defaults;
	}
	
	
	# Function assign additional actions
	public function actions ()
	{
		# Specify additional actions
		$actions = array (
			'home' => array (
				'description' => false,
				'url' => '',
				'tab' => 'Home',
				'icon' => 'house',
			),
			'conference' => array (
				'description' => 'Register for the conference',
				'url' => 'conference/',
				'tab' => 'Conference registration',
				'form' => true,
			),
			'presentations' => array (
				'description' => 'Apply to give a presentation',
				'url' => 'presentations/',
				'tab' => 'Presentation application',
				'form' => true,
			),
			'posters' => array (
				'description' => 'Apply to present a poster',
				'url' => 'posters/',
				'tab' => 'Poster application',
				'form' => true,
			),
			'fieldweek' => array (
				'description' => 'Register for the fieldweek',
				'url' => 'fieldweek/',
				'tab' => 'Fieldweek registration',
				'form' => true,
			),
			'vendor' => array (
				'description' => 'Register as a vendor',
				'url' => 'vendor/',
				'tab' => 'Vendor registration',
				'form' => true,
			),
			'edit' => array (
				'description' => false,
				'url' => '%1/edit.html',
				'usetab' => 'home',
				'authentication' => true,
			),
		);
		
		# Return the actions
		return $actions;
	}
	
	
	# Database structure definition
	public function databaseStructure ()
	{
		return "
			CREATE TABLE `administrators` (
			  `username` varchar(255 NOT NULL PRIMARY KEY COMMENT 'Username',
			  `active` enum('','Yes','No' NOT NULL DEFAULT 'Yes' COMMENT 'Currently active?'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8mb4_unicode_ci COMMENT='System administrators';
			
			CREATE TABLE `settings` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Automatic key (ignored)',
			  `feedbackRecipient` VARCHAR(255) NOT NULL COMMENT 'Recipient e-mail',
			  `conferenceIntroduction` TEXT COMMENT 'Conference page introduction',
			  `presentationsIntroduction` TEXT COMMENT 'Presentations page introduction',
			  `postersIntroduction` TEXT COMMENT 'Posters page introduction',
			  `fieldweekIntroduction` TEXT COMMENT 'Fieldweek page introduction',
			  `vendorIntroduction` TEXT COMMENT 'Vendor page introduction',
			  `sessions` TEXT NOT NULL COMMENT 'Sessions',
			  `projects` TEXT NOT NULL COMMENT 'Projects',
			  `conferenceConfirmationMailIntroduction` TEXT NULL COMMENT 'Introduction text for conference e-mail'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8mb4_unicode_ci COMMENT='Settings';
			
			CREATE TABLE `users` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Automatic key',
			  `email` varchar(191 NOT NULL COMMENT 'Your e-mail address' UNIQUE KEY,
			  `password` varchar(255 NOT NULL COMMENT 'Password',
			  `validationToken` varchar(255 DEFAULT NULL COMMENT 'Token for validation or password reset',
			  `lastLoggedInAt` datetime DEFAULT NULL COMMENT 'Last logged in time',
			  `validatedAt` datetime DEFAULT NULL COMMENT 'Time when validated',
			  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8mb4_unicode_ci COMMENT='Users';
			
			CREATE TABLE `conference` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Automatic key',
			  `title` enum('Dr','Mr','Ms','Miss','Mrs','Mx','Prof','Sir' NOT NULL COMMENT 'Title',
			  `forename` varchar(255 NOT NULL COMMENT 'Forename',
			  `surname` varchar(255 NOT NULL COMMENT 'Surname',
			  `address` text NOT NULL COMMENT 'Address, including postal code',
			  `country` varchar(255 NOT NULL COMMENT 'Country',
			  `email` varchar(255 NOT NULL COMMENT 'E-mail',
			  `participantType` enum('Presenter','Participant (non-student)','Student participant','Vendor representative' NOT NULL COMMENT 'Registering as',
			  `membership` enum('Member','None' NOT NULL COMMENT 'Professional membership',
			  `userId` INT(11) NOT NULL UNIQUE KEY COMMENT 'User ID',
			  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Automatic timestamp'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8mb4_unicode_ci COMMENT='Conference applications';
			
			CREATE TABLE `fieldweek` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Automatic key',
			  `title` enum('Dr','Mr','Ms','Miss','Mrs','Mx','Prof','Sir' NOT NULL COMMENT 'Title',
			  `forename` varchar(255 NOT NULL COMMENT 'Forename',
			  `surname` varchar(255 NOT NULL COMMENT 'Surname',
			  `address` text NOT NULL COMMENT 'Address, including postal code',
			  `country` varchar(255 NOT NULL COMMENT 'Country',
			  `email` varchar(255 NOT NULL COMMENT 'E-mail',
			  `position` enum('Academic','Student','Research','Other' NOT NULL COMMENT 'Position',
			  `membership` enum('Member','None' NOT NULL COMMENT 'Professional membership',
			  `project1` varchar(255 NOT NULL COMMENT 'Project - 1st choice',
			  `project2` varchar(255 NOT NULL COMMENT '2nd choice',
			  `project3` varchar(255 NOT NULL COMMENT '3rd choice',
			  `project4` varchar(255 DEFAULT NULL COMMENT '4th choice',
			  `project5` varchar(255 DEFAULT NULL COMMENT '5th choice',
			  `statement` text NOT NULL COMMENT 'Applicant statement',
			  `dietaryRequirements` enum('No particular requirements','Vegetarian','Vegan','Gluten-free','Other:' NOT NULL COMMENT 'Dietary requirements',
			  `dietaryDetails` varchar(255 DEFAULT NULL COMMENT 'Other (dietary request)',
			  `medical` text COMMENT 'Physical/medical concerns',
			  `userId` INT(11) NOT NULL UNIQUE KEY COMMENT 'User ID',
			  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Automatic timestamp'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8mb4_unicode_ci COMMENT='Conference applications';
			
			CREATE TABLE `presentations` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Automatic key',
			  `type` enum('Oral','Poster' NOT NULL COMMENT 'Type of presentation',
			  `session` varchar(255 NOT NULL COMMENT 'Session',
			  `title` varchar(255 NOT NULL COMMENT 'Title',
			  `name` varchar(255 NOT NULL COMMENT 'Presenter''s name',
			  `email` VARCHAR(255) NOT NULL COMMENT 'Presenter\'s e-mail',
			  `authors` text NOT NULL COMMENT 'Author(s), one per line',
			  `abstract` text NOT NULL COMMENT 'Abstract',
			  `status` ENUM('Submitted','In review','Accepted','Rejected') NOT NULL DEFAULT 'Submitted' COMMENT 'Status',
			  `review` TEXT NULL COMMENT 'Reviewer comments',
			  `userId` INT(11) NOT NULL COMMENT 'User ID',
			  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Automatic timestamp',
			  UNIQUE `typeUserId` (`type`, `userId`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8mb4_unicode_ci COMMENT='Presentation (oral/poster) applications';
			
			CREATE TABLE `vendor` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Automatic key',
			  `company` varchar(255 NOT NULL COMMENT 'Company name',
			  `title` enum('Dr','Mr','Ms','Miss','Mrs','Mx','Prof','Sir' NOT NULL COMMENT 'Title',
			  `forename` varchar(255 NOT NULL COMMENT 'Forename',
			  `surname` varchar(255 NOT NULL COMMENT 'Surname',
			  `address` text NOT NULL COMMENT 'Address, including postal code',
			  `country` varchar(255 NOT NULL COMMENT 'Country',
			  `email` varchar(255 NOT NULL COMMENT 'E-mail',
			  `website` varchar(255 NOT NULL COMMENT 'Company website',
			  `description` text NOT NULL COMMENT 'Vendor description',
			  `userId` INT(11) NOT NULL UNIQUE KEY COMMENT 'User ID',
			  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Automatic timestamp'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8mb4_unicode_ci COMMENT='Conference applications';
		";
	}
	
	
	# Additional initialisation
	public function main ()
	{
		# Set internal fields
		$this->internalFields = array ('id', 'status', 'review', 'userId', 'createdAt');
		
	}
	
	
	
	# Welcome screen
	public function home ()
	{
		# Start the HTML
		$html = "\n<p><strong>Welcome to the conference booking system.</strong></p>";
		
		# Create a list of forms
		$list = array ();
		foreach ($this->actions as $actionId => $action) {
			if (isSet ($action['form']) && $action['form']) {
				$list[$actionId] = "<a href=\"{$this->baseUrl}/{$actionId}/\">" . htmlspecialchars ($action['description']) . '</a>';
			}
		}
		$html .= "\n<p>Please select the relevant form:</p>";
		$html .= application::htmlUl ($list, 0, 'boxylist');
		
		# Show the HTML
		echo $html;
	}
	
	
	# Conference registration
	public function conference ()
	{
		# Delegate to the form
		echo $html = $this->createForm (__FUNCTION__);
	}
	
	
	# Presentation application
	public function presentations ()
	{
		# Delegate to the form
		echo $html = $this->createForm (__FUNCTION__);
	}
	
	
	# Poster application
	public function posters ()
	{
		# Delegate to the form
		echo $html = $this->createForm (__FUNCTION__, 'presentations');
	}
	
	
	# Fieldweek registration
	public function fieldweek ()
	{
		# Delegate to the form
		echo $html = $this->createForm (__FUNCTION__);
	}
	
	
	# Vendor registration
	public function vendor ()
	{
		# Delegate to the form
		echo $html = $this->createForm (__FUNCTION__);
	}
	
	
	# Form
	private function createForm ($action, $forceTable = false)
	{
		# Start the HTML
		$html = '';
		
		# Determine the table to use
		$table = $action;
		if ($forceTable) {
			$table = $forceTable;
		}
		
		# Determine any prefix to the e-mail
		$confirmationEmailIntroductoryText = false;
		if ($action == 'conference') {
			if ($this->settings['conferenceConfirmationMailIntroduction']) {
				$confirmationEmailIntroductoryText = $this->settings['conferenceConfirmationMailIntroduction'];
			}
		}
		
		# Show the user's submission if they have already made one
		if ($submission = $this->getDisplayableSubmissionOfUser ($this->user, $table, $action, $headings /* returned by reference */)) {
			
			# Show the submission
			$html  = "\n<p>You have submitted the following details:</p>";
			$html .= application::htmlTableKeyed ($submission, $headings, true, 'lines', false, true, $addRowKeyClasses = true);
			$html .= "\n<p class=\"comment\">Please contact us via the <a href=\"{$this->baseUrl}/feedback.html\">feedback page</a> if any of these details are incorrect.</p>";
			
			# Return the HTML
			return $html;
		}
		
		# Get the dataBinding attributes for each table
		$dataBindingAttributesByAction = $this->formDataBindingAttributes ();
		
		# Create a new form
		$form = new form (array (
			'div' => 'ultimateform horizontalonly',
			'displayRestrictions' => false,
			'nullText' => '',
			'formCompleteText' => $this->tick . ' Thank you for your submission. We will be in touch in due course.',
			'autofocus' => true,
			'databaseConnection' => $this->databaseConnection,
			'unsavedDataProtection' => true,
			'picker' => true,
			'cols' => 60,
			'ip' => false,
			'user' => $this->userVisibleIdentifier,
			'confirmationEmailIntroductoryText' => $confirmationEmailIntroductoryText,
		));
		if ($this->settings["{$action}Introduction"]) {
			$introductionHtml = application::formatTextBlock (application::makeClickableLinks ($this->settings["{$action}Introduction"]));
			$introductionHtml = "\n<div class=\"graybox\">" . $introductionHtml . "\n</div>";
			$form->heading ('', $introductionHtml);
		}
		$form->dataBinding (array (
			'database' => $this->settings['database'],
			'table' => $table,
			'intelligence' => true,
			'exclude' => $this->internalFields,
			'attributes' => $dataBindingAttributesByAction[$action],
		));
		
		# For the projects, enforce uniqueness
		if ($table == 'fieldweek') {
			$form->validation ('different', array ('project1', 'project2', 'project3', 'project4', 'project5'));
		}
		
		# Set output to e-mail, confirmation e-mail, and screen
		$form->setOutputEmail ($this->settings['feedbackRecipient'], $this->settings['administratorEmail'], "{$this->settings['applicationName']}: {$this->actions[$action]['description']}");
		$form->setOutputConfirmationEmail ('email', $this->settings['administratorEmail'], "{$this->settings['applicationName']}: {$this->actions[$action]['description']}", $includeAbuseNotice = false);
		$form->setOutputScreen ();
		
		# Process the form
		if ($result = $form->process ($html)) {
			
			# Inject the user ID
			$result['userId'] = $this->user;
			
			# Insert into the database
			if (!$this->databaseConnection->insert ($this->settings['database'], $table, $result)) {
				echo "\n<p class=\"warning\">There was a problem inserting the data.</p>";
				application::dumpData ($this->databaseConnection->error ());
			}
		}
		
		# Return the HTML
		return $html;
	}
	
	
	# Function to get the submission of a user for display to the user
	private function getDisplayableSubmissionOfUser ($userId, $table, $action, &$headings = array ())
	{
		# Set constraints
		$constraints = array ('userId' => $this->user);
		
		# For the hybrid presentations/poster table, set additional constraint
		if ($table == 'presentations') {
			$typeConstraints = array (
				'presentations'	=> 'Oral',
				'posters'		=> 'Poster',
			);
			$constraints['type'] = $typeConstraints[$action];
		}
		
		# Get the data
		$data = $this->databaseConnection->selectOne ($this->settings['database'], $table, $constraints);
		
		# Exclude internal fields
		$visiblePrivateFields = array ('status');	// Allow status
		$internalFields = array_diff ($this->internalFields, $visiblePrivateFields);
		foreach ($internalFields as $field) {
			unset ($data[$field]);
		}
		
		# Obtain headings
		$headings = $this->databaseConnection->getHeadings ($this->settings['database'], $table);
		
		# Return the data
		return $data;
	}
	
	
	# Helper function to define the dataBinding attributes
	private function formDataBindingAttributes ()
	{
		# Get the countries
		$countries = form::getCountries ();
		
		# Define the properties, by table
		$dataBindingAttributes = array (
			
			'conference' => array (
				'email' => array ('default' => $this->userVisibleIdentifier, 'editable' => false, ),
				'country' => array ('type' => 'select', 'values' => $countries, ),
				'participantType' => array ('type' => 'radiobuttons', ),
				'membership' => array ('type' => 'radiobuttons', ),
			),
			
			'presentations' => array (
				'type' => array ('type' => 'radiobuttons', 'default' => 'Oral', 'editable' => false, ),
				'session' => array ('type' => 'radiobuttons', 'values' => $this->settings['sessions'], ),
				'email' => array ('default' => $this->userVisibleIdentifier, 'editable' => false, ),
				'abstract' => array ('description' => 'If you would like to request some special audio or visual aids for this presentation, use this filed to inform us.'),
			),
			
			'posters' => array (
				'type' => array ('type' => 'radiobuttons', 'default' => 'Poster', 'editable' => false, ),
				'session' => array ('type' => 'radiobuttons', 'values' => $this->settings['sessions'], ),
				'email' => array ('default' => $this->userVisibleIdentifier, 'editable' => false, ),
			),
			
			'fieldweek' => array (
				'country' => array ('type' => 'select', 'values' => $countries, ),
				'email' => array ('default' => $this->userVisibleIdentifier, 'editable' => false, ),
				'position' => array ('type' => 'radiobuttons', ),
				'membership' => array ('type' => 'radiobuttons', ),
				'project1' => array ('type' => 'select', 'values' => $this->settings['projects'], 'heading' => array (3 => 'Project choice'), ),
				'project2' => array ('type' => 'select', 'values' => $this->settings['projects'], ),
				'project3' => array ('type' => 'select', 'values' => $this->settings['projects'], ),
				'project4' => array ('type' => 'select', 'values' => $this->settings['projects'], ),
				'statement' => array ('heading' => array ('p' => 'Due to limitation of space, only 40 applications will be accepted. To be considered all applications must be accompanied by a brief statement describing how the applicant will use this experience in their future studies. This statement must be no more than 500 words in length. Of the 40 spaces available, 10 full scholarships will be awarded to successful applicants from developing countries. No other financial assistance will be provided.', )),
				'project5' => array ('type' => 'select', 'values' => $this->settings['projects'], ),
				'dietaryRequirements' => array ('type' => 'radiobuttons', 'heading' => array (3 => 'Personal requirements'), ),
				'medical' => array ('description' => 'Please use this field to let us know of any physical or medical conditions we should be aware of to improve your participation.', ),
			),
			
			'vendor' => array (
				'country' => array ('type' => 'select', 'values' => $countries, ),
				'email' => array ('default' => $this->userVisibleIdentifier, 'editable' => false, ),
				'website' => array ('placeholder' => 'https://...', 'description' => false, ),
				'description' => array ('heading' => array ('p' => 'In the field below please describe what it is you wish to display, why you think it is appropriate for this conference, and what, if any, special requirements you need to present your materials effectively.'), ),
			),
		);
		
		# Return the properties
		return $dataBindingAttributes;
	}
	
	
	# Admin editing section, substantially delegated to the sinenomine editing component
	public function editing ($attributes = array (), $deny = false, $sinenomineExtraSettings = array ())
	{
		# Define sinenomine settings
		$sinenomineExtraSettings = array (
			'int1ToCheckbox' => true,
			'simpleJoin' => true,
			'datePicker' => true,
			'richtextEditorToolbarSet' => 'BasicLonger',
			'richtextWidth' => 600,
			'richtextHeight' => 200,
		);
		
		# Define table attributes
		$attributesByTable = $this->formDataBindingAttributes ($table);
		$attributes = array ();
		foreach ($attributesByTable as $table => $attributesForTable) {
			foreach ($attributesForTable as $field => $fieldAttributes) {
				$attributes[] = array ($this->settings['database'], $table, $field, $fieldAttributes);
			}
		}
		
		# Define tables to deny editing for
		$deny[$this->settings['database']] = array (
			'administrators',
			'settings',
			'users',
		);
		
		# Hand off to the default editor, which will echo the HTML
		parent::editing ($attributes, $deny, $sinenomineExtraSettings);
	}
}

?>
