describe('Item sidemenu actions', () => {

    beforeEach(() => {
        cy.visitSceneForChapter();
        cy.registerAjaxContainerAlias();
        cy.get('.side-menu ul li').contains('Przedmioty').parents('li').first().as('items');
    });

    it('adds an item from another scene and removes it', () => {
        cy.get('@items').within(() => {
            cy.get('.js-load-sublist').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Dodawanie istniejącego przedmiotu').should('be.visible');
            cy.get('@ajax-container').get('td').contains('Przedmiot 2').parent().get('.js-list-action').click();
            cy.contains('Dodano przedmiot "Przedmiot 2" do sceny "Scena 1".').should('be.visible');
        });

        cy.get('@items').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.sublist-label').contains('Przedmiot 2').next().find(".js-delete.btn-warning").click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Usunięto przedmiot "Przedmiot 2" ze sceny "Scena 1".').should('be.visible');
            });
        });
    });
});
