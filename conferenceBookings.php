<?php

# Class to create a conference bookings system


require_once ('frontControllerApplication.php');
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
				'description' => 'Conference registration',
				'url' => 'conference/',
				'tab' => 'Conference registration',
				'form' => true,
			),
			'presentations' => array (
				'description' => 'Presentation application',
				'url' => 'presentations/',
				'tab' => 'Presentation application',
				'form' => true,
			),
			'fieldweek' => array (
				'description' => 'Fieldweek registration',
				'url' => 'fieldweek/',
				'tab' => 'Fieldweek registration',
				'form' => true,
			),
			'vendor' => array (
				'description' => 'Vendor registration',
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
			  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Username' PRIMARY KEY,
			  `active` enum('','Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes' COMMENT 'Currently active?'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='System administrators';
			
			CREATE TABLE `settings` (
			  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic key (ignored)' PRIMARY KEY,
			  `recipientEmail` VARCHAR(255) NOT NULL COMMENT 'Recipient e-mail'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Settings';
			
			CREATE TABLE `users` (
			  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic key' PRIMARY KEY,
			  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Your e-mail address' UNIQUE KEY,
			  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Password',
			  `validationToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Token for validation or password reset',
			  `lastLoggedInAt` datetime DEFAULT NULL COMMENT 'Last logged in time',
			  `validatedAt` datetime DEFAULT NULL COMMENT 'Time when validated',
			  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Users';
			
			CREATE TABLE `conference` (
			  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic key' PRIMARY KEY,
			  `title` enum('Dr','Mr','Ms','Miss','Mrs','Mx','Prof','Sir') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Title',
			  `forename` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Forename',
			  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Surname',
			  `address` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Address, including postal code',
			  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Country',
			  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'E-mail',
			  `participantType` enum('Presenter','Participant (non-student)','Student participant','Vendor representative') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Registering as',
			  `membership` enum('Tree-ring Society (TRS)','Association for Tree-ring Research (ATR)','None') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Professional membership',
			  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Automatic timestamp'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Conference application';
			
			CREATE TABLE `fieldweek` (
			  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic key' PRIMARY KEY,
			  `title` enum('Dr','Mr','Ms','Miss','Mrs','Mx','Prof','Sir') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Title',
			  `forename` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Forename',
			  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Surname',
			  `address` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Address, including postal code',
			  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Country',
			  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'E-mail',
			  `position` enum('Academic','Student','Research','Other') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Position',
			  `membership` enum('Tree-ring Society (TRS)','Association for Tree-ring Research (ATR)','None') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Professional membership',
			  `project1` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Project - 1st choice',
			  `project2` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '2nd choice',
			  `project3` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '3rd choice',
			  `project4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '4th choice',
			  `project5` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '5th choice',
			  `statement` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Applicant statement',
			  `dietaryRequirements` enum('Vegetarian','Vegan','Gluten-free','Other') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Dietary requirements',
			  `dietaryDetails` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Other (dietary request)',
			  `medical` text COLLATE utf8_unicode_ci COMMENT 'Physical/medical concerns',
			  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Automatic timestamp'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Conference application';
			
			CREATE TABLE `presentations` (
			  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic key' PRIMARY KEY,
			  `type` enum('Oral','Poster') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Type of presentation',
			  `session` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Session',
			  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Title',
			  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Presenter''s name',
			  `email` VARCHAR(255) NOT NULL COMMENT 'Presenter\'s e-mail',
			  `authors` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Author(s), one per line',
			  `abstract` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Abstract',
			  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Automatic timestamp'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Presentations (oral/poster)';
			
			CREATE TABLE `vendor` (
			  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic key' PRIMARY KEY,
			  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Company name',
			  `title` enum('Dr','Mr','Ms','Miss','Mrs','Mx','Prof','Sir') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Title',
			  `forename` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Forename',
			  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Surname',
			  `address` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Address, including postal code',
			  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Country',
			  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'E-mail',
			  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Company website',
			  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Vendor description',
			  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Automatic timestamp'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Conference application';
		";
	}
	
	
	# Additional initialisation
	public function main ()
	{
		
	}
	
	
	
	# Welcome screen
	public function home ()
	{
		# Start the HTML
		$html = "\n<p>Welcome to the conference booking system.</p>";
		
		# Create a list of forms
		$list = array ();
		foreach ($this->actions as $actionId => $action) {
			if (isSet ($action['form']) && $action['form']) {
				$list[$actionId] = "<a href=\"{$this->baseUrl}/{$actionId}/\">" . htmlspecialchars ($action['description']) . '</a>';
			}
		}
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
	private function createForm ($action)
	{
		# Start the HTML
		$html = '';
		
		# Create a new form
		$form = new form (array (
			'div' => 'lines form',
			'displayRestrictions' => false,
			'nullText' => '',
			'formCompleteText' => $this->tick . ' Thank you for your submission. We will be in touch in due course.',
			'autofocus' => true,
			'databaseConnection' => $this->databaseConnection,
			'unsavedDataProtection' => true,
			'picker' => true,
			'cols' => 70,
		));
		$form->dataBinding (array (
			'database' => $this->settings['database'],
			'table' => $action,
			'intelligence' => true,
			'attributes' => $this->formDataBindingAttributes (),
		));
		$form->setOutputEmail ($this->settings['feedbackRecipient'], $this->settings['administratorEmail'], "{$this->settings['applicationName']}: {$this->actions[$action]['description']}");
		$form->setOutputScreen ();
		if ($result = $form->process ($html)) {
			
			# Insert into the database
			if (!$this->databaseConnection->insert ($this->settings['database'], $action, $result)) {
				echo "\n<p class=\"warning\">There was a problem inserting the data.</p>";
				application::dumpData ($this->databaseConnection->error ());
			}
		}
		
		# Return the HTML
		return $html;
	}
	
	
	# Helper function to define the dataBinding attributes
	private function formDataBindingAttributes ()
	{
		# Define the properties
		$dataBindingAttributes = array (
			'email' => array ('default' => $this->userVisibleIdentifier, ),
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
		$attributes = array (
		);
		
		# Define tables to deny editing for
		$deny[$this->settings['database']] = array (
			'administrators',
			'settings',
		);
		
		# Hand off to the default editor, which will echo the HTML
		parent::editing ($attributes, $deny, $sinenomineExtraSettings);
	}
}

?>
