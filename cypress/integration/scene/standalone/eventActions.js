describe('Event sidemenu actions', () => {

    beforeEach(() => {
    cy.visitStandaloneScene();
    cy.registerAjaxContainerAlias();
    cy.get('.side-menu ul li').contains('Wydarzenia').parents('li').first().as('events');
    });

    it('creates, edits and deletes an event', () => {
        cy.get('@events').within(() => {
            cy.get('.js-list-toggle[title="Nowe wydarzenie"]').click();
            cy.get('.js-load-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Nowe wydarzenie').should('be.visible');
            cy.get('@ajax-container').get('[name="create[name]"]').type('Spotkanie');
            cy.get('@ajax-container').get('[name="create[model][root]"]').select('Postać do spotkania 1');
            cy.get('@ajax-container').get('[name="create[model][location]"]').select('Miejsce do spotkania 1');
            cy.get('@ajax-container').get('[name="create[model][relation]"]').select('Postać do spotkania 2');
            cy.get('@ajax-container').contains('Zapisz').click();
            cy.contains('Pomyślnie dodano nowe wydarzenie o nazwie "Spotkanie"').should('be.visible');
        });

        cy.get('@events').within(() => {
            cy.get('.js-list-toggle.btn-default').click();
            cy.contains(/^Spotkanie$/).next().find('.js-display').click();
        }).then(() => {
            cy.get('#modal-display h4').contains(/^Spotkanie$/).should('be.visible');
            cy.get('#modal-display').contains('Zamknij').click();
            cy.get('@events').find('.js-list-toggle.btn-default').click();
        });

        cy.get('@events').within(() => {
            cy.get('.js-list-toggle[title="Lista"]').click();
            cy.get('.js-load-form.js-edit-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Edycja wydarzenia').should('be.visible');
            cy.get('@ajax-container').get('[name="edit[name]"]').type(' edytowane');
            cy.get('@ajax-container').get('[name="edit[model][root]"]').select('Postać do spotkania 2');
            cy.get('@ajax-container').get('[name="edit[model][location]"]').select('Miejsce do spotkania 2');
            cy.get('@ajax-container').get('[name="edit[model][relation]"]').select('Postać do spotkania 3');
            cy.get('@ajax-container').contains('Zapisz').click();
            cy.contains('Zapisano zmiany w wydarzeniu.').should('be.visible');
        });

        cy.get('@events').within(() => {
            cy.get('.js-list-toggle[title="Lista"]').click();
            cy.get('.js-delete').last().click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Wydarzenie "Spotkanie edytowane" zostało usunięte.').should('be.visible');
            });
        });
    });
});