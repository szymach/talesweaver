describe('Character actions', () => {

    beforeEach(() => {
        cy.visitSceneForChapter();
    });

    it('asserts character with one scene cannot be removed from it', () => {
        cy.clickTab('Postacie').then(() => {
            cy.contains(/^Postać 1$/).parent().find('[title="Usuń ze sceny"]').should('not.be.visible')
        });
    });

    it('adds a character from another scene and removes it', () => {
        cy.clickTab('Postacie').then(() => {
            cy.get('#characters').find('[title="Dodaj postać z innej sceny"]').click().then(() => {
                cy.get('#modal-list').contains('Dodawanie istniejącej postaci').should('be.visible');
                cy.get('#modal-list').get('td').contains('Postać 2').parent().get('.js-list-action').click().then(() => {
                    cy.contains('Dodano postać "Postać 2" do sceny "Scena 1".').should('be.visible').parent().find('button').click();
                });
            });
        });

        cy.clickTab('Postacie').then(() => {
            cy.get('#characters').contains('Postać 2').parent().find('[title="Usuń ze sceny"]').click().then(() => {
                cy.get('#modal-delete').contains('Potwierdzenie akcji').should('be.visible');
                cy.get('#modal-delete').contains('Tak').click().then(() => {
                    cy.contains('Usunięto postać "Postać 2" ze sceny "Scena 1".').should('be.visible').parent().find('button').click();
                });
            });
        });
    });
});
