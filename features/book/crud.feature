Feature: Book CRUD operations
    As the application's user I want to be able to perform basic CRUD operations
    on books.

    Scenario: Book list view
        Given I am on the page for "listing books"
        Then the table should have the following columns:
            | Name    |
            | Title   |
            | Actions |

    Scenario: Opening the page for creating books
        Given I am on the page for "listing books"
        And I click the "Add" button
        Then I should be on the page for "creating books"

    Scenario: Adding a new book
        Given I am on the page for "creating books"
        And I fill out the form for a new book "Book 1"
        And I submit the form
        Then I should be on the page for editing book "Book 1"

    Scenario: Opening the page for editing books
        Given the fixtures file "basic_books.yml" is loaded
        And I am on the page for "listing books"
        And I click the "Edit" button in the "first" row
        Then I should be on the page for editing books for the "first" book

    Scenario: Editing a book
        Given the fixtures file "basic_books.yml" is loaded
        And I am on the page for editing book "Book 1"
        And I modify the book form
        And I submit the form
        Then I should be on the page for editing book "Modified book"
        And there should be no errors
