Feature: Basic CRUD operations
    When using the library in it's most basic form, I want to be able to add
    books and assign chapters to them. Also I want to be able to add scenes to
    chapters

    Scenario: Scene list view
        Given I am on the page for "listing scenes"
        Then the table should have the following columns:
            | Name    |
            | Book    |
            | Chapter |
            | Title   |
            | Actions |

    @wip
    Scenario: Opening the page for creating scenes
        Given I am on the page for listing scenes
        And I click the "Add" button
        Then I should be on the page for creating scenes

    @wip
    Scenario: Adding a new scene
        Given I am on the page for creating scenes
        And I fill out the form for a new scene
        And I submit the form
        Then I should be on the page for editing scenes

    @wip
    Scenario: Opening the page for editing scenes
        Given I am on the page for listing scenes
        And there is a scene defined
        And I click the "Edit" button in the first row
        Then I should be on the page for editing scenes

    @wip 
    Scenario: Editing a scene
        Given I am on the page for editing scenes
        And I modify the scene form
        And I submit the form
        Then I should be on the page for editing scenes
        And there should be no errors
