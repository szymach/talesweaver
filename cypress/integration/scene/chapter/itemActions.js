describe('Item sidemenu actions', () => {

    beforeEach(() => {
        cy.visitSceneForChapter();
    });

    it('asserts item with one scene cannot be removed from it', () => {
        cy.clickTab('Przedmioty').then(() => {
            cy.contains(/^Przedmiot 1$/).next().find('[title="Usuń ze sceny"]').should('not.be.visible')
        });
    });

    it('adds a item from another scene and removes it', () => {
        cy.clickTab('Przedmioty').then(() => {
            cy.get('#items').find('[title="Dodaj przedmiot z innej sceny"]').click().then(() => {
                cy.get('.modal').contains('Dodawanie istniejącego przedmiotu');
                cy.get('.modal').get('td').contains('Przedmiot 2').parent().get('.js-list-action').click().then(() => {
                    cy.contains('Dodano przedmiot "Przedmiot 2" do sceny "Scena 1".').should('be.visible');
                });
            });
        });

        cy.clickTab('Przedmioty').then(() => {
            cy.get('#items').contains('Przedmiot 2').parent().find('[title="Usuń ze sceny"]').click().then(() => {
                cy.get('.modal').contains('Potwierdzenie akcji');
                cy.get('.modal').contains('Tak').click().then(() => {
                    cy.contains('Usunięto przedmiot "Przedmiot 2" ze sceny "Scena 1".').should('be.visible');
                });
            });
        });
    });
});
