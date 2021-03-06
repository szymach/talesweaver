describe('Item actions', () => {

    beforeEach(() => {
        cy.visitSceneForChapter();
        cy.clickTab('Przedmioty');
    });

    it('asserts item with one scene cannot be removed from it', () => {
        cy.contains(/^Przedmiot 1$/).parent().find('[title="Usuń ze sceny"]').should('not.be.visible');
    });

    it('adds a item from another scene and removes it', () => {
        cy.get('#items').find('[title="Dodaj przedmiot z innej sceny"]').click().then(() => {
            cy.get('#modal-list').contains('Dodawanie istniejącego przedmiotu').should('be.visible');
            cy.get('#modal-list').get('td').contains('Przedmiot 2').parent().get('.js-list-action').click().then(() => {
                cy.contains('Dodano przedmiot "Przedmiot 2" do sceny "Scena 1".').should('be.visible').parent().find('button').click();
            });
        });

        cy.get('#items').contains('Przedmiot 2').parent().find('[title="Usuń ze sceny"]').click().then(() => {
            cy.get('#modal-delete').contains('Potwierdzenie akcji').should('be.visible');
            cy.get('#modal-delete').contains('Tak').click().then(() => {
                cy.contains('Usunięto przedmiot "Przedmiot 2" ze sceny "Scena 1".').should('be.visible').parent().find('button').click();
            });
        });
    });
});
