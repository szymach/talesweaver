describe('Event actions', () => {

    beforeEach(() => {
        cy.visitStandaloneScene();
    });

    it('creates, displays, edits and deletes an event', () => {
        cy.clickTab('Wydarzenia').then(() => {
            cy.get('#events').find('[title="Nowe wydarzenie"]').click().then(() => {
                cy.get('.modal').contains('Nowe wydarzenie').should('be.visible');
                cy.get('.modal').find('input[name="create[name]"]').type('Spotkanie');
                cy.get('.modal').contains('Zapisz').click();
                cy.get('#alerts').contains('Pomyślnie dodano nowe wydarzenie o nazwie "Spotkanie".').should('be.visible').parent().find('button').click();
            });

            cy.get('#events').contains(/^Spotkanie$/).parent().find('.js-display').click().then(() => {
                cy.get('.modal').contains(/^Spotkanie$/).should('be.visible');
                cy.get('.modal').contains('Zamknij').click();
                cy.get('.modal').should('not.be.visible');
            });

            cy.get('#events').contains(/^Spotkanie$/).parent().find('.js-edit-form').click().then(() => {
                cy.get('.modal').contains('Edycja wydarzenia');
                cy.get('.modal').find('input[name="edit[name]"]').type('{selectall}Spotkanie edytowane');
                cy.get('.modal').contains('Zapisz').click();
                cy.get('#alerts').contains('Zapisano zmiany w wydarzeniu.').should('be.visible').parent().find('button').click();
            });

            cy.get('#events').contains('Spotkanie edytowane').parent().find('.js-delete').click().then(() => {
                cy.get('#modal-confirm').click().then(() => {
                    cy.get('#alerts').contains('Wydarzenie "Spotkanie edytowane" zostało usunięte.').should('be.visible').parent().find('button').click();
                });
            });
        });
    });
});
