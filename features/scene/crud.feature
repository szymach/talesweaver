Feature: Scene CRUD operations
    As the application's user I want to be able to perform basic CRUD operations
    on scenes.

    Scenario: Scene list view
        Given I am on the page for "listing scenes"
        Then the table should have the following columns:
            | Name    |
            | Book    |
            | Chapter |
            | Title   |
            | Actions |

    Scenario: Opening the page for creating scenes
        Given I am on the page for "listing scenes"
        And I click the "Add" button
        Then I should be on the page for "creating scenes"

    Scenario: Adding a new scene
        Given I am on the page for "creating scenes"
        And I fill out the form for a new scene "Scene 1"
        And I submit the form
        Then I should be on the page for editing scene "Scene 1"

    Scenario: Opening the page for editing scenes
        Given the fixtures file "basic_scenes.yml" is loaded
        And I am on the page for "listing scenes"
        And I click the "Edit" button in the "first" row
        Then I should be on the page for editing scenes for the "first" scene

    Scenario: Editing a scene
        Given the fixtures file "basic_scenes.yml" is loaded
        And I am on the page for editing scene "Scene 1"
        And I modify the scene form
        And I submit the form
        Then I should be on the page for editing scene "Modified scene"
        And there should be no errors
