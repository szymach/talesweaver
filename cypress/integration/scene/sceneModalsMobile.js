describe('Modal opening', function() {

    beforeEach(function () {
        cy.request({
            method: 'POST',
            url: '/login',
            form: true,
            body: {
                '_username': 'user@example.com',
                '_password': 'password'
            }
        }).then(function (response) {
            expect(response.status).to.eq(200);
        });

        cy.visit('/scene/list');
        cy.url().should('eq', Cypress.config('baseUrl') + '/scene/list');
        cy.get('.btn-primary[title="Edycja"]').click();
        cy.url().should('contain', Cypress.config('baseUrl') + '/scene/edit');
        cy.viewport(768, 800);
        cy.get('#ajax-container').as('ajax-container');
        cy.get('.side-menu ul [title="Postacie"]').as('characters');
        cy.get('.side-menu ul [title="Przedmioty"]').as('items');
        cy.get('.side-menu ul [title="Miejsca"]').as('locations');
        cy.get('.side-menu ul [title="Wydarzenia"]').as('events');
    });


    it('opens mobile lists', function () {
        cy.get('@characters').click().then(() => {
            cy.get('.h4').contains('Postacie').should('be.visible');
            cy.get('.h4').contains('Przedmioty').should('not.be.visible');
            cy.get('.h4').contains('Miejsca').should('not.be.visible');
            cy.get('.h4').contains('Wydarzenia').should('not.be.visible');

            cy.get('@characters').parent().find('.js-list-toggle[title="Lista"]').click().then(() => {
                cy.get('@characters').parent().find('.js-list-container').should('be.visible');
                cy.get('@characters').parent().find('[title="Nowa postać"]').click().then(() => {
                    cy.get('@ajax-container').contains('Nowa postać').should('be.visible');
                });
            });
        });

        cy.get('@items').click().then(() => {
            cy.get('.h4').contains('Postacie').should('not.be.visible');
            cy.get('.h4').contains('Przedmioty').should('be.visible');
            cy.get('.h4').contains('Miejsca').should('not.be.visible');
            cy.get('.h4').contains('Wydarzenia').should('not.be.visible');

            cy.get('@items').parent().find('.js-list-toggle[title="Lista"]').click().then(() => {
                cy.get('@items').parent().find('.js-list-container').should('be.visible');
                cy.get('@items').parent().find('[title="Nowy przedmiot"]').click().then(() => {
                    cy.get('@ajax-container').contains('Nowy przedmiot').should('be.visible');
                });
            });
        });

        cy.get('@locations').click().then(() => {
            cy.get('.h4').contains('Postacie').should('not.be.visible');
            cy.get('.h4').contains('Przedmioty').should('not.be.visible');
            cy.get('.h4').contains('Miejsca').should('be.visible');
            cy.get('.h4').contains('Wydarzenia').should('not.be.visible');

            cy.get('@locations').parent().find('.js-list-toggle[title="Lista"]').click().then(() => {
                cy.get('@locations').parent().find('.js-list-container').should('be.visible');
                cy.get('@locations').parent().find('[title="Nowe miejsce"]').click().then(() => {
                    cy.get('@ajax-container').contains('Nowe miejsce').should('be.visible');
                });
            });
        });

        cy.get('@events').click().then(() => {
            cy.get('.h4').contains('Postacie').should('not.be.visible');
            cy.get('.h4').contains('Przedmioty').should('not.be.visible');
            cy.get('.h4').contains('Miejsca').should('not.be.visible');
            cy.get('.h4').contains('Wydarzenia').should('be.visible');

            cy.get('@events').parent().find('.js-list-toggle[title="Lista"]').click().then(() => {
                cy.get('@events').parent().find('.js-list-container').should('be.visible');
                cy.get('@events').parent().find('[title="Nowe wydarzenie"]').click().then(() => {
                    cy.get('@events').parent().find('.js-list-container').should('be.visible');
                    cy.get('@events').parent().find('.js-list-container [title="Nowe spotkanie"]').click().then(() => {
                        cy.get('@ajax-container').contains('Nowe wydarzenie').should('be.visible');
                    });
                });
            });
        });
    });
});
