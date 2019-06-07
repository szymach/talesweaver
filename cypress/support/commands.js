Cypress.Commands.add("login", (email, password) => {
    cy.request({
        method: 'POST',
        url: '/login',
        form: true,
        body: { '_email': email, '_password': password }
    }).then(function (response) {
        expect(response.status).to.eq(200);
    });
});

Cypress.Commands.add("visitStandaloneScene", () => {
    cy.visit('/scene/list');
    cy.url().should('eq', Cypress.config('baseUrl') + '/scene/list');
    cy.get('tr').contains(/^Scena$/).parent().find('[title="Edycja"]').click().then(() => {
        cy.url().should('contain', Cypress.config('baseUrl') + '/scene/edit');
    });
});

Cypress.Commands.add("visitSceneForChapter", () => {
    cy.visit('/chapter/list');
    cy.url().should('eq', Cypress.config('baseUrl') + '/chapter/list');
    cy.get('tr').contains(/^Rozdział$/).parent().find('[title="Edycja"]').click().then(() => {
        cy.url().should('contain', Cypress.config('baseUrl') + '/chapter/edit');

        cy.get('.nav-tabs').contains('Sceny').click();
        cy.get('#scenes').as('scenes');
        cy.get('@scenes').within(() => {
            cy.contains(/^Scena 1$/).next().find('[title="Edycja"]').click().then(() => {
                cy.url().should('contain', Cypress.config('baseUrl') + '/scene/edit');
            })
        });
    });
});

Cypress.Commands.add('clickTab', (tab) => {
    cy.get('.nav-tabs').contains(tab).click().wait(500);
});