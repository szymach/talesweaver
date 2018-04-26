Cypress.Commands.add("login", (email, password) => {
    cy.request({
        method: 'POST',
        url: '/login',
        form: true,
        body: { '_username': email, '_password': password }
    }).then(function (response) {
        expect(response.status).to.eq(200);
    });
})

Cypress.Commands.add("visitStandaloneScene", () => {
    cy.visit('/scene/list');
    cy.url().should('eq', Cypress.config('baseUrl') + '/scene/list');
    cy.get('tr').contains('Scena').parent().find('[title="Edycja"]').click().then(() => {
        cy.url().should('contain', Cypress.config('baseUrl') + '/scene/edit');
    });
});

Cypress.Commands.add("registerAjaxContainerAlias", () => {
    cy.get('#ajax-container').as('ajax-container');
});
