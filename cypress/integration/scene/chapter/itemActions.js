describe('Item sidemenu actions', () => {

    beforeEach(() => {
        cy.visitSceneForChapter();
        cy.registerAjaxContainerAlias();
        cy.get('.side-menu ul li').contains('Przedmioty').parents('li').first().as('items');
    });

    it('asserts item with one scene cannot be removed from it', () => {
        cy.get('@items').within(() => {
            cy.get('.js-list-toggle').click();
        }).then(() => {
            cy.contains(/^Przedmiot 1$/).next().find('.js-list-delete.js-delete.btn-warning').should('not.be.visible')
        });
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
