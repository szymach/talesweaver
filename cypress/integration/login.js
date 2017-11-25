describe('Visits login page', function() {
  it('finds the header and login form', function() {
    cy.visit('/');

    cy.contains('Bajkopisarz');
    cy.contains('Email');
    cy.contains('Hasło');
    cy.contains('Zaloguj');
    cy.contains('Resetowanie hasła');
    cy.contains('Rejestracja');
  })
})
