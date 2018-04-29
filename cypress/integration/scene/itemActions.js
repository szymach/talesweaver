describe('Item sidemenu actions', () => {

    beforeEach(() => {
        cy.visitStandaloneScene();
        cy.registerAjaxContainerAlias();
        cy.get('.side-menu ul li').contains('Przedmioty').parents('li').first().as('items');
    });

    it('creates new item', () => {
        cy.get('@items').within(() => {
            cy.get('.js-load-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Nowy przedmiot').should('be.visible');
            cy.get('@ajax-container').get('input[name="create[name]"]').type('Przedmiot{enter}');
            cy.contains('Pomyślnie dodano nowy przedmiot o nazwie "Przedmiot"').should('be.visible');
        });
    });

    it('edits existing item', () => {
        cy.get('@items').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.js-edit-form').last().click();
        }).then(() => {
            cy.contains('Edycja przedmiotu');
            cy.get('input[name="edit[name]"]').type(' edytowany{enter}');
            cy.contains('Zapisano zmiany w przedmiocie.').should('be.visible');
        });
    });

    it('deletes an item', () => {
        cy.get('@items').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.js-delete').last().click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Przedmiot "Przedmiot edytowany" został usunięty.').should('be.visible');
            });
        });
    });
});
