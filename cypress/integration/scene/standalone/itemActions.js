describe('Item sidemenu actions', () => {

    beforeEach(() => {
        cy.visitStandaloneScene();
    });

    it('creates, displays, edits and deletes a item', () => {
        cy.clickTab('Przedmioty').then(() => {
            cy.get('#items').find('[title="Nowy przedmiot"]').click().then(() => {
                cy.get('.modal').contains('Nowy przedmiot');
                cy.get('.modal').find('input[name="create[name]"]').type('Przedmiot');
                cy.get('.modal').contains('Zapisz').click();
                cy.contains('Pomyślnie dodano nowy przedmiot o nazwie "Przedmiot"').should('be.visible');
            });

            cy.get('#items').contains(/^Przedmiot$/).parent().find('.js-display').click().then(() => {
                cy.get('.modal').contains(/^Przedmiot$/);
                cy.get('.modal').contains('Zamknij').click();
                cy.get('.modal').should('not.be.visible');
            });

            cy.get('#items').contains(/^Przedmiot$/).parent().find('.js-edit-form').click().then(() => {
                cy.get('.modal').contains('Edycja przedmiotu');
                cy.get('.modal').find('input[name="edit[name]"]').type('{selectall}Przedmiot edytowany');
                cy.get('.modal').contains('Zapisz').click();
                cy.contains('Zapisano zmiany w przedmiocie.').should('be.visible');
            });

            cy.get('#items').contains('Przedmiot edytowany').parent().find('.js-delete').click().then(() => {
                cy.get('#modal-confirm').click().then(() => {
                    cy.contains('Przedmiot "Przedmiot edytowany" został usunięty.').should('be.visible');
                });
            });
        });
    });
});
