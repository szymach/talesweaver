Cypress.Commands.add("login", (email, password) => {
    cy.request({
        method: 'POST',
        url: '/login',
        form: true,
        body: { '_username': email, '_password': password }
    }).then(function (response) {
        expect(response.status).to.eq(200);
    });
});

Cypress.Commands.add("visitStandaloneBook", () => {
    cy.visit('/book/list');
    cy.url().should('eq', Cypress.config('baseUrl') + '/book/list');
    cy.get('tr').contains(/^Książka$/).parent().find('[title="Edycja"]').click().then(() => {
        cy.url().should('contain', Cypress.config('baseUrl') + '/book/edit');
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

        cy.get('.side-menu ul li').contains('Sceny').parents('li').first().as('scenes');
        cy.get('@scenes').find('.js-list-toggle').click().then(() => {
            cy.get('@scenes').within(() => {
                cy.contains(/^Scena 1$/).next().find('[title="Edycja"]').click().then(() => {
                    cy.url().should('contain', Cypress.config('baseUrl') + '/scene/edit');
                })
            });
        });
    });
});

Cypress.Commands.add("registerAjaxContainerAlias", () => {
    cy.get('#ajax-container').as('ajax-container');
});
