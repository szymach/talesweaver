Feature: Chapter CRUD operations
    As the application's user I want to be able to perform basic CRUD operations
    on chapters.

    Scenario: Chapter list view
        Given I am on the page for "listing chapters"
        Then the table should have the following columns:
            | Name    |
            | Book    |
            | Title   |
            | Actions |

    Scenario: Opening the page for creating chapters
        Given I am on the page for "listing chapters"
        And I click the "Add" button
        Then I should be on the page for "creating chapters"

    Scenario: Adding a new chapter
        Given I am on the page for "creating chapters"
        And I fill out the form for a new chapter "Chapter 1"
        And I submit the form
        Then I should be on the page for editing chapter "Chapter 1"

    Scenario: Opening the page for editing chapters
        Given the fixtures file "basic_chapters.yml" is loaded
        And I am on the page for "listing chapters"
        And I click the "Edit" button in the "first" row
        Then I should be on the page for editing chapters for the "first" chapter

    Scenario: Editing a chapter
        Given the fixtures file "basic_chapters.yml" is loaded
        And I am on the page for editing chapter "Chapter 1"
        And I modify the chapter form
        And I submit the form
        Then I should be on the page for editing chapter "Modified chapter"
        And there should be no errors
