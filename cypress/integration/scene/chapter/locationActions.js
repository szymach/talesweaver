describe('Location sidemenu actions', () => {

    beforeEach(() => {
        cy.visitSceneForChapter();
    });

    it('asserts location with one scene cannot be removed from it', () => {
        cy.clickTab('Miejsca').then(() => {
            cy.contains(/^Miejsce 1$/).next().find('[title="Usuń ze sceny"]').should('not.be.visible')
        });
    });

    it('adds a location from another scene and removes it', () => {
        cy.clickTab('Miejsca').then(() => {
            cy.get('#locations').find('[title="Dodaj miejsce z innej sceny"]').click().then(() => {
                cy.get('.modal').contains('Dodawanie istniejącego miejsca').should('be.visible');
                cy.get('.modal').get('td').contains('Miejsce 2').parent().get('.js-list-action').click().then(() => {
                    cy.contains('Dodano miejsce "Miejsce 2" do sceny "Scena 1".').should('be.visible');
                });
            });
        });

        cy.clickTab('Miejsca').then(() => {
            cy.get('#locations').contains('Miejsce 2').parent().find('[title="Usuń ze sceny"]').click().then(() => {
                cy.get('.modal').contains('Potwierdzenie akcji').should('be.visible');
                cy.get('.modal').contains('Tak').click().then(() => {
                    cy.contains('Usunięto miejsce "Miejsce 2" ze sceny "Scena 1".').should('be.visible');
                });
            });
        });
    });
});
