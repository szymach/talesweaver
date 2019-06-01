describe('Character sidemenu actions', () => {

    beforeEach(() => {
        cy.visitStandaloneScene();
    });

    it('creates, displays, edits and deletes a character', () => {
        cy.clickTab('Postacie').then(() => {
            cy.get('#characters').find('[title="Nowa postać"]').click().then(() => {
                cy.get('.modal').contains('Nowa postać').should('be.visible');
                cy.get('.modal').find('input[name="create[name]"]').type('Postać');
                cy.get('.modal').contains('Zapisz').click();
                cy.contains('Pomyślnie dodano nową postać o imieniu "Postać"').should('be.visible');
            });

            cy.get('#characters').contains(/^Postać$/).parent().find('.js-display').click().then(() => {
                cy.get('.modal').contains(/^Postać$/).should('be.visible');
                cy.get('.modal').contains('Zamknij').click();
                cy.get('.modal').should('not.be.visible');
            });

            cy.get('#characters').contains(/^Postać$/).parent().find('.js-edit-form').click().then(() => {
                cy.get('.modal').contains('Edycja postaci').should('be.visible');
                cy.get('.modal').find('input[name="edit[name]"]').type('{selectall}Postać edytowana');
                cy.get('.modal').contains('Zapisz').click();
                cy.contains('Zapisano zmiany w postaci.').should('be.visible');
            });

            cy.get('#characters').contains('Postać edytowana').parent().find('.js-delete').click().then(() => {
                cy.get('#modal-confirm').click().then(() => {
                    cy.contains('Postać "Postać edytowana" została usunięta.').should('be.visible');
                });
            });
        });
    });
});
