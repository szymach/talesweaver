describe('Location actions', () => {

    beforeEach(() => {
        cy.visitSceneForChapter();
        cy.clickTab('Miejsca');
    });

    it('asserts location with one scene cannot be removed from it', () => {
        cy.contains(/^Miejsce 1$/).parent().find('[title="Usuń ze sceny"]').should('not.be.visible');
    });

    it('adds a location from another scene and removes it', () => {
        cy.get('#locations').find('[title="Dodaj miejsce z innej sceny"]').click().then(() => {
            cy.get('#modal-list').contains('Dodawanie istniejącego miejsca').should('be.visible');
            cy.get('#modal-list').get('td').contains('Miejsce 2').parent().get('.js-list-action').click().then(() => {
                cy.contains('Dodano miejsce "Miejsce 2" do sceny "Scena 1".').should('be.visible').parent().find('button').click();
            });
        });

        cy.get('#locations').contains('Miejsce 2').parent().find('[title="Usuń ze sceny"]').click().then(() => {
            cy.get('#modal-delete').contains('Potwierdzenie akcji').should('be.visible');
            cy.get('#modal-delete').contains('Tak').click().then(() => {
                cy.contains('Usunięto miejsce "Miejsce 2" ze sceny "Scena 1".').should('be.visible').parent().find('button').click();
            });
        });
    });
});
