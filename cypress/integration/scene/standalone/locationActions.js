describe('Location actions', () => {

    beforeEach(() => {
        cy.visitStandaloneScene();
    });

    it('creates, displays, edits and deletes a location', () => {
        cy.clickTab('Miejsca').then(() => {
            cy.get('#locations').find('[title="Nowe miejsce"]').click().then(() => {
                cy.get('.modal').contains('Nowe miejsce').should('be.visible');
                cy.get('.modal').find('input[name="create[name]"]').type('Miejsce');
                cy.get('.modal').contains('Zapisz').click();
                cy.contains('Pomyślnie dodano nowe miejsce o nazwie "Miejsce"').should('be.visible').parent().find('button').click();
            });

            cy.get('#locations').contains(/^Miejsce$/).parent().find('.js-display').click().then(() => {
                cy.get('.modal').contains(/^Miejsce$/).should('be.visible');
                cy.get('.modal').contains('Zamknij').click();
                cy.get('.modal').should('not.be.visible');
            });

            cy.get('#locations').contains(/^Miejsce$/).parent().find('.js-edit-form').click().then(() => {
                cy.get('.modal.show').scrollTo('top');
                cy.get('.modal').contains('Edycja miejsca').should('be.visible');
                cy.get('.modal').find('input[name="edit[name]"]').type('{selectall}Miejsce edytowane');
                cy.get('.modal').contains('Zapisz').click();
                cy.contains('Zapisano zmiany w miejscu.').should('be.visible').parent().find('button').click();
            });

            cy.get('#locations').contains('Miejsce edytowane').parent().find('.js-delete').click().then(() => {
                cy.get('#modal-confirm').click().then(() => {
                    cy.contains('Miejsce "Miejsce edytowane" zostało usunięte.').should('be.visible').parent().find('button').click();
                });
            });
        });
    });
});
